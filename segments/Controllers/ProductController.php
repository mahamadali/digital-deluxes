<?php

namespace Controllers;

use Bones\Request;
use Models\Product;
use Models\ProductDeveloper;
use Models\ProductPublisher;
use Models\ProductGenres;
use Models\ProductOffer;
use Models\ProductScreenshot;

class ProductController
{
	public function syncProduct(Request $request)
	{
		$products = callKinguinApi('/v1/products', ['page' => 1, 'limit' => 1]);

		if (!$products){
			return response()->json(['status' => 501, 'message' => 'Something went wrong!']);
		}

		$total_products = $products->item_count;
		$page = 1;
		$limit = 100;
		$total_pages = ceil($total_products / $limit);

		for($i = 1; $i <= $total_pages; $i++){
		
			$products = callKinguinApi('/v1/products', ['page' => $i, 'limit' => $limit]);

			foreach($products->results as $kproduct){
				
				$is_product_exist = $this->checkProductExist($kproduct->productId);
				
				if(empty($is_product_exist)){
					$product = new Product();
					$product->name = $kproduct->name ?? null;
					$product->description = $kproduct->description ?? null;
					$product->coverImage = $kproduct->coverImage ?? null;
					$product->coverImageOriginal = $kproduct->coverImageOriginal ?? null;
					$product->platform = $kproduct->platform ?? null;
					$product->releaseDate = $kproduct->releaseDate ?? null;
					$product->qty = $kproduct->qty ?? null;
					$product->textQty = $kproduct->textQty ?? null;
					$product->price = $kproduct->price ?? null;
					$product->regionalLimitations = $kproduct->regionalLimitations ?? null;
					$product->regionId = $kproduct->regionId ?? null;
					$product->activationDetails = $kproduct->activationDetails ?? null;
					$product->kinguinId = $kproduct->kinguinId ?? null;
					$product->productId = $kproduct->productId ?? null;
					$product->originalName = $kproduct->originalName ?? null;
					$product->offersCount = $kproduct->offersCount ?? null;
					$product->totalQty = $kproduct->totalQty ?? null;
					$product->ageRating = $kproduct->ageRating ?? null;
					$product->steam = $kproduct->steam ?? null;
					$product->updated_at = $kproduct->updatedAt;
					$product = $product->save();

					if(!empty($kproduct->developers)){
						foreach($kproduct->developers as $developer){
							$product_developer = new ProductDeveloper();
							$product_developer->product_id  = $product->id;
							$product_developer->name  = $developer;
							$product_developer->save();
						}
					}

					if(!empty($kproduct->publishers)){
						foreach($kproduct->publishers as $publisher){
							$product_publisher = new ProductPublisher();
							$product_publisher->product_id  = $product->id;
							$product_publisher->name  = $publisher;
							$product_publisher->save();
						}
					}

					if(!empty($kproduct->genres)){
						foreach($kproduct->genres as $genres){
							$product_genres = new ProductGenres();
							$product_genres->product_id  = $product->id;
							$product_genres->name  = $genres;
							$product_genres->save();
						}
					}

					if(!empty($kproduct->offers)){
						foreach($kproduct->offers as $offer){
							$product_offer = new ProductOffer();
							$product_offer->product_id  = $product->id;
							$product_offer->name  = $offer->name;
							$product_offer->offerId  = $offer->offerId;
							$product_offer->price  = $offer->price;
							$product_offer->qty  = $offer->qty;
							$product_offer->textQty  = $offer->textQty;
							$product_offer->merchantName  = $offer->merchantName;
							$product_offer->isPreorder  = $offer->isPreorder;
							$product_offer->releaseDate  = $offer->releaseDate;
							$product_offer->save();
						}
					}


					if(!empty($kproduct->screenshots)){
						foreach($kproduct->screenshots as $screenshot){
							$product_screenshot = new ProductScreenshot();
							$product_screenshot->product_id  = $product->id;
							$product_screenshot->url  = $screenshot->url;
							$product_screenshot->url_original  = $screenshot->url_original;
							$product_screenshot->save();
						}
					}
					

					// $cheapestOfferId = $kproduct->cheapestOfferId;

					// $videos = $kproduct->videos;

					// $languages = $kproduct->languages;

					// $systemRequirements = $kproduct->systemRequirements;

					// $tags = $kproduct->tags;

					// $merchantName = $kproduct->merchantName;	
				}else{

					$product = Product::where('productId',$kproduct->productId)->first();
					$product->name = $kproduct->name ?? '';
					$product->description = $kproduct->description ?? '';
					$product->coverImage = $kproduct->coverImage ?? '';
					$product->coverImageOriginal = $kproduct->coverImageOriginal ?? '';
					$product->platform = $kproduct->platform ?? '';
					$product->releaseDate = $kproduct->releaseDate ?? '';
					$product->qty = $kproduct->qty;
					$product->textQty = $kproduct->textQty;
					$product->price = $kproduct->price;
					$product->regionalLimitations = $kproduct->regionalLimitations;
					$product->regionId = $kproduct->regionId;
					$product->activationDetails = $kproduct->activationDetails ?? '';
					$product->kinguinId = $kproduct->kinguinId;
					$product->productId = $kproduct->productId;
					$product->originalName = $kproduct->originalName;
					$product->offersCount = $kproduct->offersCount;
					$product->totalQty = $kproduct->totalQty;
					$product->ageRating = $kproduct->ageRating;
					$product->steam = $kproduct->steam ?? '';
					$product->updated_at = $kproduct->updatedAt;
					$product->save();

				}
				

			}

			
			
			

			echo "<pre>";
			print_r($products);
			exit;
			
			
			
		}
		
	}

	public function checkProductExist($productId){
		return Product::where('productId',$productId)->first();
	}
}