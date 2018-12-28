<?php
namespace App\Middlewares;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Actions\Auth as AuthAction;
use Slim\Container;

class Auth extends Middleware{
	/**@var AuthAction $auth*/
	protected $auth;

	public function __construct(Container $container, ?AuthAction $auth = null) {
		parent::__construct($container);
		$this->auth = $auth ?? new AuthAction($container);
	}

	public function process(ServerRequestInterface $rq, ResponseInterface $res, callable $next) {
		[$response, $user] = $this->auth->loginfromRemember($rq, $res)->asArray();
		return $next($rq, $response);
	}
}