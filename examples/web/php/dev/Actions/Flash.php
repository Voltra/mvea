<?php

namespace App\Actions;


use Slim\Container;
use Slim\Flash\Messages as FlashMessages;

class Flash extends Action{
	const SUCCESS = "success";
	const ERROR = "error";
	const INFO = "info";

	/**@var FlashMessages $flash*/
	protected $flash;

	public function __construct(Container $container) {
		parent::__construct($container);

		$this->flash = $container->flash;
	}

	public function success(string $msg){
		$this->flash->addMessage/*Now*/(self::SUCCESS, $msg);
	}

	public function error(string $msg){
		$this->flash->addMessage/*Now*/(self::ERROR, $msg);
	}

	public function info(string $msg){
		$this->flash->addMessage/*Now*/(self::INFO, $msg);
	}
}