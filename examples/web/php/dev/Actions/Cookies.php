<?php
namespace App\Actions;


use Dflydev\FigCookies\Cookie;
use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Cookies extends Action{
	public function builder(string $name): SetCookie{
		return SetCookie::create($name);
	}

	public function set(ResponseInterface $res, SetCookie $cookie): ResponseInterface{
		return FigResponseCookies::set($res, $cookie);
	}

	public function get(ServerRequestInterface $rq, string $name, ?string $default = null): Cookie{
		return FigRequestCookies::get($rq, $name, $default);
	}

	public function has(ServerRequestInterface $rq, string $name): bool{
		return !is_null($this->get($rq, $name)->getValue());
	}

	public function expire(ResponseInterface $res, string $name): ResponseInterface{
		return FigResponseCookies::expire($res, $name);
	}

	public function remove(ResponseInterface $res, string $name): ResponseInterface{
		return FigResponseCookies::remove($res, $name);
	}
}