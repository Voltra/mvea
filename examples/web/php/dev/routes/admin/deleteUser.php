<?php

use App\Filters\UserFilter;
use App\Filters\AdminFilter;
use App\Models\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App as SlimApp;
use Slim\Http\Response;


/**@var SlimApp $app*/
$app->get("/admin/delete/{uid}", function(ServerRequestInterface $rq, Response $res, array $args): ResponseInterface{
	/**@var \Slim\Container $this*/
	$uid = intval($args["uid"]);
	User::destroy($uid);
	return $res->withRedirect($this->router->pathFor("admin.dashboard"));
})->setName("admin.deleteUser")
->add(
	UserFilter::from($app->getContainer())
	->composeWith(AdminFilter::from($app->getContainer()))
);