<?php
namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Container;
use App\Actions\Csrf as CsrfAction;

class Csrf extends Middleware {
	/**@var CsrfAction $csrf*/
	protected $csrf;

	protected const METHODS = ["POST", "PUT", "DELETE"];

	public function __construct(Container $container) {
		parent::__construct($container);
		$this->csrf = new CsrfAction($container);
	}

	public function process(ServerRequestInterface $rq, ResponseInterface $res, callable $next): ResponseInterface{
		$token = $this->csrf->getToken();

		if(in_array($rq->getMethod(), static::METHODS)){
			$submittedToken = $rq->
		}
	}
}