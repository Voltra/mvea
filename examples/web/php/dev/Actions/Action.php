<?php
namespace App\Actions;

use Slim\Container;

abstract class Action{
	/**@var Container $container*/
	protected $container;

	public function __construct(Container $container) {
		$this->container = $container;
	}
};