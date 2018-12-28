<?php
namespace App\Middlewares;

use App\Exceptions\CsrfTokenMismatch;
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
		$this->csrf->ensureHasToken();
		$key = $this->csrf->formKey();

		if(in_array($rq->getMethod(), static::METHODS)){
			$params = $rq->getParsedBody();
			$submittedToken = isset($params[$key])
			? $params[$this->csrf->formKey()]
			: "";

			if(!$this->csrf->isValid($submittedToken))
				throw new CsrfTokenMismatch();
		}

		//TODO: put data in the view
		$data = [
			"csrf_key" => $key,
			"csrf_token" => $this->csrf->getToken()
		];

		return $next($rq, $res);
	}
}