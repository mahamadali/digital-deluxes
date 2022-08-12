<?php

namespace Models;

use Models\Base\Model;
use Models\Role;
use Models\Traits\TrashMask;
use Models\Cart;


class User extends Model
{
	use TrashMask;

	protected $table = 'users';
	protected $attaches = ['full_name'];
	protected $with = ['role'];

	protected $defaults = [];

	public function role() 
	{
		return $this->parallelTo(Role::class, 'role_id');
	}

	public function getFullNameProperty()
	{
		return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
	}

	public function cart_items(){
		return $this->hasMany(Cart::class, 'user_id')->get();
	}
	


}