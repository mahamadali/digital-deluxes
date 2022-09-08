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
	protected $attach = ['price', 'price_original'];

	public $price_original_value = 0;

    public function offers(){
		return $this->hasMany(ProductOffer::class, 'product_id')->get();
	}

	public function offer(){
		return $this->hasOne(ProductOffer::class, 'product_id');
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

	public function isInWishlist() {
		$product_id = $this->id;
		$wishlist = UserWishlist::where('user_id', auth()->id)->where('product_id', $product_id)->first();
		if(!empty($wishlist)) {
			return true;
		}
		return false;
	}

	public function getPriceOriginalProperty() {
		$profitPrice = getProfitPrice($this->price_original_value);
		return $profitPrice;
	}
	
	public function getPriceProperty() {
		$price = $this->price;
		$this->price_original_value = $price;
		
		if(!session()->has('base_price') && empty(session()->get('base_price'))) {
			session()->setCurrency('cop');
			$base_price = currencyConverter('EUR', 'COP', 1);
			session()->set('base_price', $base_price);
		}
		
		$currenctCurrencyPrice = (float) session()->get('base_price') * getProfitPrice($price);
		
		$profitPrice = $currenctCurrencyPrice;
		return $profitPrice;
	}

	public function manual_keys() {
		return $this->hasMany(ProductKeys::class, 'product_id');
	}
}