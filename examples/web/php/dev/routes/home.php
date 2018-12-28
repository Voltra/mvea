<?php
use Slim\App as SlimApp;
use Slim\Container;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**@var SlimApp $app*/
$app->get("/", function(ServerRequestInterface $rq, ResponseInterface $res){
	/**@var Container $this*/

	print_r($this); die(); //TODO: Debug this

	return $this->view->render($res, "home.twig", [
		"variable" => "world"
	]);
})->setName("home");