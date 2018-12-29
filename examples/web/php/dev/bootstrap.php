<?php
require_once "../vendor/autoload.php";

use App\Path;
use Slim\App as SlimApp;


require_once "env.php";
$config = require_once("config.php");
$db = require_once("db.php");

$app = new SlimApp([
	"config" => $config,
	"settings" => $config["settings"]
]);
$container = $app->getContainer();

session_start();
foreach(require_once(Path::dev("/container/actions.php")) as $class => $factory)
	$container[$class] = $factory;

$container["session"] = require_once(Path::dev("/container/session.php"));
$container["flash"] = require_once(Path::dev("/container/flash.php"));
$container["view"] = require_once(Path::dev("/container/view.php"));

$app->add(new \Slim\Middleware\Session($config["session"]))
	->add(App\Middlewares\Csrf::from($container))
	->add(App\Middlewares\Auth::from($container));

require_once "route_autoload.php";

return $app;