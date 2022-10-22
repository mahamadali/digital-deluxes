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
	protected $attach = ['price', 'price_original', 'slug'];

	public $price_original_value = 0;

    public function offers(){
		return $this->hasMany(ProductOffer::class, 'product_id')->get();
	}

	public function offer(){
		return $this->hasOne(ProductOffer::class, 'product_id');
	}

	public function featuredImage(){
		return $this->hasOne(ProductScreenshot::class, 'product_id')->first();
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
		
		if(!session()->has('base_price') || empty(session()->get('base_price'))) {
			session()->set('platform_currency', 'cop');
			$base_price = currencyConverter('EUR', 'COP', 1);
			session()->set('base_price', $base_price);
		}
		
		
		$currenctCurrencyPrice = (float) session()->get('base_price') * (float) getProfitPrice($price);
		
		$profitPrice = $currenctCurrencyPrice;
		
		if(session()->get('platform_currency') == 'cop') {
			$profitPrice = number_format($profitPrice, 0, ',', '.');
		}

		if(session()->get('platform_currency') == 'usd' || session()->get('platform_currency') == 'eur') {
			$profitPrice = round($profitPrice, 2);
		}
		
		return $profitPrice;
	}

	public function manual_keys() {
		return $this->hasMany(ProductKeys::class, 'product_id');
	}

	public function manual_keys_available() {
		return $this->hasMany(ProductKeys::class, 'product_id')->where('is_used', 0);
	}

	public function platform_logos() {
		return $this->hasMany(PlatformLogo::class, 'platform', 'platform');
	}
	public function home_slider() {
    	return $this->hasOne(HomeSliderProduct::class, 'product_id');
    }

	public function isInSlider() {
		$homeSlider = HomeSliderProduct::where('product_id', $this->id)->first();
		if(!empty($homeSlider)) {
			return true;
		} else {
			return false;
		}
	}

	public function getSlugProperty() {
        $text = $this->name;
        $divider = '-';
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}