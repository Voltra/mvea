<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class User extends Model{
//	protected $table = "users";
	protected $fillable = ["username", "password"];

	public function roles(){
		/*return $this->hasManyThrough(
			Role::class, UserRole::class,
			"user_id", "id",
			"id", "role_id"
		);*/
		return $this->belongsToMany(Role::class)
		->using(UserRole::class)
//		->as("granted")
		->withTimestamps();
	}

	public function permissions(){
		return $this->roles()->flatMap(function(Role $role){
			return $role->permissions();
		})->uniqueStrict(function(Permission $permission){
			return $permission->id;
		});
	}

	public function admin(){
		return $this->hasOne(Admin::class);
	}

	public function remember(){
		return $this->hasOne(UserRemember::class);
	}

	public function isAdmin(): bool{
		return !is_null($this->admin);
	}

	public function updateRemember(?string $id, ?string $token): bool{
		return $this->remember->updateCredentials($id, $token);
	}

	public function resetRemember(): bool{
		return $this->remember->reset();
	}

	public function scopeFromUsername(/*Builder*/ $query, string $username): ?self{
		return $query->where("username", $username)->first();
	}

	public static function make(string $username, string $password): self{
		return self::create([
			"username" => $username,
			"password" => $password
		]);
	}

}