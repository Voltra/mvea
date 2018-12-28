<?php
namespace App\Middlewares;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Container;

abstract class Middleware {
	/**@var Container $container*/
	protected $container;

	public function __invoke(ServerRequestInterface $rq, ResponseInterface $res, callable $next){
		return $this->process($rq, $res, $next);
	}

	public abstract function process(ServerRequestInterface $rq, ResponseInterface $res, callable $next);

	public static function from(...$args){
		return new static(...$args);
	}

	public function __construct(Container $container){
		$this->container = $container;
	}
}