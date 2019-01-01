<?php

use App\Filters\VisitorFilter;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App as SlimApp;
use Slim\Http\Response;
use Slim\Http\StatusCode;


/**@var SlimApp $app*/
$app->post("/login", function(ServerRequestInterface $rq, Response $res): Response{
	/**@var \Slim\Container $this*/
	$post = $rq->getParsedBody();
	$intersect = array_intersect_key($post, ["username", "password", "pconfirm", "remember"]);
	$router = $this->router;
	$goBack = function(Response $res, $status) use($router): Response{
		return $res->withRedirect($router->pathFor("login"), $status);
	};

	if(empty($intersect))
		return $goBack($res, StatusCode::HTTP_BAD_REQUEST);

	[
		"username" => $username,
		"password" => $password,
		"pconfirm" => $confirm,
		"remember" => $remember
	] = $post;

	/**@var \App\Actions\Flash $flash*/
	$flash = $this->get(\App\Actions\Flash::class);
	if($password !== $confirm){
		$flash->failure("Passwords don't match");
		return $goBack($res, StatusCode::HTTP_CONFLICT);
	}

	$remember = boolval($remember);
	/**@var \App\Actions\Auth $auth*/
	$auth = $this->get(\App\Actions\Auth::class);
	/**@var Response $response*/
	$response = $auth->login($res, $username, $password, $remember)->response;

	if(!$auth->isLoggedIn()) {
		$flash->failure("Invalid credentials");
		return $goBack($response, StatusCode::HTTP_CONFLICT);
	}

	$flash->success("Successfully logged in");
	return $response->withRedirect($router->pathFor("home"));
})->setName("login.post")
->add(VisitorFilter::from($app->getContainer()));