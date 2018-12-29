<?php
namespace App\Filters;


use App\Actions\Auth;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;
use Slim\Router;

abstract class Filter {
	/**@var Container $container*/
//	protected $container;

	/**@var Router $router*/
	protected $router;

	/**@var Auth $auth*/
	protected $auth;

	public function __construct(ContainerInterface $c) {
//		$this->container = $c;
		$this->router = $c->get("router");
		$this->auth = $c->get(Auth::class);
	}

	public static function from(ContainerInterface $c){
		return new static($c);
	}

	protected abstract function isAuthorized(/*Container $c*/): bool;

	protected function redirectURL(): string{
		return $this->router->urlFor("home");
	}

	protected function redirectStatus(): int{
		return StatusCode::HTTP_FORBIDDEN;
	}

	public function __invoke(Request $rq, Response $res, callable $next): Response{
		if(!$this->isAuthorized(/*$this->container*/))
			return $res->withRedirect($this->redirectURL());

		return $next($rq, $res);
	}
}