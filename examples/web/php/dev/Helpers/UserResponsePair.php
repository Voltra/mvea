<?php
namespace App\Helpers;


use App\Models\User;
use Psr\Http\Message\ResponseInterface;

class UserResponsePair {
	/**
	 * @var ResponseInterface $response
	 * @var User|null $user
	 */
	public $user, $response;

	public function __construct(ResponseInterface $res, ?User $user = null) {
		$this->res = $res;
		$this->user = $user;
	}

	public function asArray(): array{
		return [$this->res, $this->user];
	}
}