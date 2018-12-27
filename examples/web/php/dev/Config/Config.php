<?php
namespace App\Config;

use Noodlehaus\Config as HassankhanConfig;

class Config extends HassankhanConfig{
	protected function getDefaults() {
		return [
			"debug" => false,
			"settings" => [
				"displayErrorDetails" => false
			],
			"db" => [
				"driver" => "mysql",
				"host" => "localhost",
				"port" => "3306",
				"database" => "",
				"username" => "",
				"password" => "",
				"charset" => "",
				"collation" => "",
				"prefix" => "",
			]
		];
	}
}