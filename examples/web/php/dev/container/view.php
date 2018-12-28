<?php

use App\Path;
use Slim\Container;
use Slim\Http\Environment;
use Slim\Http\Uri;
use Slim\Views\Twig;
use Slim\Views\TwigExtension as BaseTwigTools;

return function(Container $c){
	$config = $c["config"];
	$view = new Twig(Path::dev("/views"), $config["views"]);
	$router = $c->router;
	$uri = Uri::createFromEnvironment(new Environment($_SERVER));

	$view->addExtension(new BaseTwigTools($router, $uri));
	return $view;
};