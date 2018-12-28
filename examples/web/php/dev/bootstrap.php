<?php
require_once "../vendor/autoload.php";
use Slim\App as SlimApp;


require_once "env.php";
$config = require_once("config.php");
$db = require_once("db.php");

$app = new SlimApp([
	"config" => $config,
	"settings" => $config["settings"]
]);

$app->add(new \Slim\Middleware\Session($config["session"]));
$app->getContainer()["session"] = function($c){
	return new \SlimSession\Helper();
};

require_once "route_autoload.php";

return $app;