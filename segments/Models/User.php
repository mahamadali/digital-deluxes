<?php

namespace Models;

use Models\Base\Model;
use Models\Role;
use Models\Traits\TrashMask;
use Models\Cart;


class User extends Model
{
	// use TrashMask;

	protected $table = 'users';
	protected $attaches = ['full_name'];
	protected $with = ['role', 'wishlists', 'orders', 'country_info'];

	protected $defaults = [];

	public function role() 
	{
		return $this->parallelTo(Role::class, 'role_id');
	}

	public function wishlists() 
	{
		return $this->hasMany(UserWishlist::class, 'user_id')->orderBy('id');
	}

	public function getFullNameProperty()
	{
		return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
	}

	public function cart_items(){
		return $this->hasMany(Cart::class, 'user_id')->get();
	}

	public function orders(){
		return $this->hasMany(Order::class, 'user_id')->without('user')->orderBy('id');
	}

	public function country_info(){
		return $this->parallelTo(Country::class, 'country');
	}
	


}