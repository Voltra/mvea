<?php
namespace App\Helpers;


use App\Models\User;
use Slim\Http\Response;

class UserResponsePair {
	/**
	 * @var Response $response
	 * @var User|null $user
	 */
	public $user, $response;

	public function __construct(Response $res, ?User $user = null) {
		$this->response = $res;
		$this->user = $user;
	}

	public function asArray(): array{
		return [$this->res, $this->user];
	}
}