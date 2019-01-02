<?php

use App\Models\User;
use Slim\App as SlimApp;
use Slim\Container;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**@var SlimApp $app*/
$app->get("/", function(ServerRequestInterface $rq, ResponseInterface $res){
	/**@var Container $this*/

	return $this->view->render($res, "home.twig", [
		"variable" => "world",
		"users" => User::all()->filter(function($user){ return !$user->isAdmin(); }),
		"self" => $this->get("user")
	]);
})->setName("home");