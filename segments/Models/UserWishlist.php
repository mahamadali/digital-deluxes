<?php

namespace Models;

use Models\Base\Model;

class UserWishlist extends Model
{
	protected $table = 'user_wishlist';

	protected $with = ['product'];

	public function product() 
	{
		return $this->parallelTo(Product::class, 'product_id');
	}

}