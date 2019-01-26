<?php
use App\Config\Config;
use App\Path;

$configMode = json_decode(utf8_encode(
	file_get_contents(Path::dev("/configMode.json"))
));
return Config::load(Path::dev("/Config/{$configMode}.php"));