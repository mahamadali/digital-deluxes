<?php

namespace Models;

use Models\Base\Model;
use Models\ProductOffer;
use Models\ProductScreenshot;
use Models\ProductSystemRequirement;
use Models\ProductVideo;


class Product extends Model
{
    protected $table = 'products';

    public function offers(){
		return $this->hasMany(ProductOffer::class, 'product_id')->get();
	}

    public function screenshots(){
		return $this->hasMany(ProductScreenshot::class, 'product_id')->get();
	}

    public function videos(){
		return $this->hasMany(ProductVideo::class, 'product_id')->get();
	}

    public function systemRequirements(){
		return $this->hasMany(ProductSystemRequirement::class, 'product_id')->get();
	}
}