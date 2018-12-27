<?php
use Slim\App as SlimApp;
use Slim\Container;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**@var SlimApp $app*/
$app->get("/", function(RequestInterface $rq, ResponseInterface $res){
	/**@var Container $this*/

	return $res->getBody()->write("Hello World!");
})->setName("home");