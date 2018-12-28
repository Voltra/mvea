<?php
namespace App\Actions;


use App\Helpers\UserResponsePair;
use App\Models\User;
use App\Models\UserRemember;
use Carbon\Carbon;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Container;
use SlimSession\Helper as Session;

class Auth extends Action{
	protected $sessionKey, $cookieName, $cookieExpiry, $separator, $containerKey;

	/**@var Hash $hash*/
	protected $hash;

	/**@var Cookies $cookies*/
	protected $cookies;

	/**@var Random $random*/
	protected $random;

	/**@var Session $session*/
	protected $session;

	public function __construct(Container $container) {
		parent::__construct($container);
		$config = $this->container["config"]["auth"];

		$this->sessionKey = $config["session"];
		$this->cookieName = $config["remember_name"];
		$this->cookieExpiry = $config["remember_exp"];
		$this->separator = $config["cookie_separator"];
		$this->containerKey = $config["container"];
		$this->session = $this->container["session"];

		$this->hash = new Hash($container);
		$this->cookies = new Cookies($container);
		$this->random = new Random($container);
	}

	/**
	 * Getter for the hash action
	 * @return Hash
	 */
	public function hasher(): Hash{
		return $this->hash;
	}

	/**
	 * Syncs the container state's with the session's
	 */
	protected function syncContainerAndSession(){
		if($this->session->exists($this->sessionKey))
			//always sync in case of user hotswap
			$this->container[$this->containerKey] = User::find($this->session->get($this->sessionKey));
		else if($this->container->has($this->containerKey))
			$this->session->set($this->sessionKey, $this->container[$this->containerKey]->id);
	}

	/**
	 * Determines whether or not a user is logged in
	 * @return bool
	 */
	public function isLoggedIn(): bool{
		$this->syncContainerAndSession();
		return $this->container->has($this->containerKey);
	}

	/**
	 * Fetches the current logged in user
	 * @return User|null
	 * @throws \Interop\Container\Exception\ContainerException
	 */
	public function user(): ?User{
		$this->syncContainerAndSession();
		if($this->isLoggedIn())
			return $this->container->get($this->containerKey);
		return null;
	}

	/**
	 * Determines whether or not the user is logged as the on with the given username
	 * @param string $username
	 * @return bool
	 * @throws \Interop\Container\Exception\ContainerException
	 */
	public function isLoggedAs(string $username): bool{
		$this->syncContainerAndSession();
		if(!$this->isLoggedIn())
			return false;

		$user = $this->user();
		return $user->username === $username;
	}

	/**
	 * Attempts to log in with the given credentials
	 * @param ResponseInterface $res
	 * @param string $username
	 * @param string $password
	 * @param bool $remember
	 * @return UserResponsePair
	 */
	public function login(ResponseInterface $res, string $username, string $password, bool $remember = false): UserResponsePair{
		$this->syncContainerAndSession();
		$user = User::where("username", $username)->first();
		if($user && $this->hasher()->checkPassword($password, $user->password)) {
			$ret = $this->logout($res);
			$this->container[$this->containerKey] = $user;

			if($remember)
				$ret = $this->remember($ret);

			$this->syncContainerAndSession();
			return new UserResponsePair($ret, $user);
		}
		return new UserResponsePair($res, null);
	}

	public function remember(ResponseInterface $res): ResponseInterface{
		$this->syncContainerAndSession();
		$user = $this->user();

		if(is_null($user))
			return $res;

		$id = $this->random->generateString();
		$token = $this->random->generateString();
		$user->updateRemember($id, $this->hasher()->hash($token));

		$cookie = $this->cookies->builder($this->cookieName)
		->withValue("{$id}{$this->separator}{$token}")
		->withExpires(Carbon::parse($this->cookieExpiry)->timestamp)
		->withHttpOnly(true);

		return $this->cookies->set($res, $cookie);
	}

	/**
	 * Forces login with the given username
	 * @param ResponseInterface $res
	 * @param string $username
	 * @return UserResponsePair
	 */
	public function forceLogin(ResponseInterface $res, string $username): UserResponsePair{
		$this->syncContainerAndSession();
		$user = User::where("username", $username)->first();

		if($user){
			$ret = $this->logout($res);
			$this->container[$this->containerKey] = $user;
			$this->syncContainerAndSession();
		}

		return new UserResponsePair($ret, $user);
	}

	/**
	 * Logs out the current user
	 * @param ResponseInterface $res
	 * @return ResponseInterface
	 */
	public function logout(ResponseInterface $res): ResponseInterface{
		$res = $this->cookies->remove($res, $this->cookieName);
		$this->session->delete($this->sessionKey);
		$this->container->offsetUnset($this->containerKey);
		return $res;
	}

	/**
	 * Register a new user
	 * @param ResponseInterface $res
	 * @param string $username
	 * @param string $password
	 * @return UserResponsePair
	 */
	public function register(ResponseInterface $res, string $username, string $password): UserResponsePair{
		$this->syncContainerAndSession();
		User::create([
			"username" => $username,
			"password" => $this->hasher()->hashPassword($password)
		]);
		return $this->login($res, $username, $password);
	}

	/**
	 * Attempt to login from remember credentials in a cookie
	 * @param ServerRequestInterface $rq
	 * @param ResponseInterface $res
	 * @return UserResponsePair
	 * @throws \Interop\Container\Exception\ContainerException
	 */
	public function loginfromRemember(ServerRequestInterface $rq, ResponseInterface $res): UserResponsePair{
		$this->syncContainerAndSession();
		$hasCookie = $this->cookies->has($rq, $this->cookieName);
		if($this->isLoggedIn() || !$hasCookie)
			return $this->user();

		/**@var string $cookie*/
		$cookie = $this->cookies->get($rq, $this->cookieName)->getValue();
		$credentials = explode($this->separator, $cookie);
		$emptyRes = new UserResponsePair($res, null);

		if(empty(trim($cookie)) || count($credentials) !== 2)
			return $emptyRes;

		$id = $credentials[0];
		$token = $credentials[1];
		$hashedToken = $this->hasher()->hash($token);
		$rem = UserRemember::where("remember_id", $id)->first();

		if(is_null($rem) || !$this->hasher()->checkHash($hashedToken, $rem->token()))
			return $emptyRes;

		$user = $rem->user;
		return $this->forceLogin($res, $user->username);
	}
}