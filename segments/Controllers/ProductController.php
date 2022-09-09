<?php

namespace Controllers;

use Bones\Request;
use Models\CurrencyRate;
use Models\Product;
use Models\ProductDeveloper;
use Models\ProductPublisher;
use Models\ProductGenres;
use Models\ProductOffer;
use Models\ProductScreenshot;
use Models\ProductVideo;
use Models\ProductSystemRequirement;


class ProductController
{
	public function syncProduct(Request $request)
	{
		ini_set('max_execution_time', 0);
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
					$product->cheapestOfferId = $kproduct->cheapestOfferId ? json_encode($kproduct->cheapestOfferId) :  null;
					$product->languages = $kproduct->languages ? json_encode($kproduct->languages) :  null;
					$product->tags = $kproduct->tags ? json_encode($kproduct->tags) :  null;
					$product->merchantName = $kproduct->merchantName ?? null ? json_encode($kproduct->merchantName) :  null;
					$product->developers = $kproduct->developers ?? null ? json_encode($kproduct->developers) :  null;
					$product->publishers = $kproduct->publishers ?? null ? json_encode($kproduct->publishers) :  null;
					$product->genres = $kproduct->genres  ?? null ? json_encode($kproduct->genres) :  null;
					$product->updated_at = date('y-m-d h:i:s');
					$product = $product->save();

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

					if(!empty($kproduct->videos)){
						foreach($kproduct->videos as $video){
							$product_video = new ProductVideo();
							$product_video->product_id  = $product->id;
							$product_video->name  = $video->name;
							$product_video->video_id  = $video->video_id;
							$product_video->save();
						}
					}

					if(!empty($kproduct->systemRequirements)){
						foreach($kproduct->systemRequirements as $systemRequirement){
							$product_system_requirement = new ProductSystemRequirement();
							$product_system_requirement->product_id  = $product->id;
							$product_system_requirement->system  = $systemRequirement->system;
							$product_system_requirement->requirement  = $systemRequirement->requirement ? json_encode($systemRequirement->requirement) : null;
							$product_system_requirement->save();
						}
					}
				}else{
					$product = Product::where('productId',$kproduct->productId)->first();

					$to_time = strtotime(date('Y-m-d h:i:s'));
					$from_time = strtotime($product->updated_at);
					$difference =  round(abs($to_time - $from_time) / 60);
					if($difference > 15){
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
						$product->cheapestOfferId = $kproduct->cheapestOfferId ? json_encode($kproduct->cheapestOfferId) :  null;
						$product->languages = $kproduct->languages ? json_encode($kproduct->languages) :  null;
						$product->tags = $kproduct->tags ? json_encode($kproduct->tags) :  null;
						$product->merchantName = $kproduct->merchantName  ?? null ? json_encode($kproduct->merchantName) :  null;
						$product->developers = $kproduct->developers ?? null ? json_encode($kproduct->developers) :  null;
						$product->publishers = $kproduct->publishers ?? null ? json_encode($kproduct->publishers) :  null;
						$product->genres = $kproduct->genres  ?? null ? json_encode($kproduct->genres) :  null;
						$product->updated_at = date('y-m-d h:i:s');
						$product->save();

					}

					
					
				}
			}

			// echo "<pre>";
			// print_r($products);
			// exit;
			
		}

		echo "Products sync successfully.";
		
	}

	public function checkProductExist($productId){
		return Product::where('productId',$productId)->first();
	}

	public function productUpdate(Request $request) {
		// $raw_post_data = '{"kinguinId":40762,"productId":"5c9b6ccc2539a4e8f17dda5c","qty":63,"textQty":63,"price":9.51,"cheapestOfferId":["61f176260995060001d8b1ba"],"updatedAt":"2022-08-15T11:58:30.959+00:00"}';
		$raw_post_data = file_get_contents('php://input'); 
        file_put_contents('product-update.txt', $raw_post_data);
		$data = json_decode($raw_post_data);
		$product = Product::where('productId', $data->productId)->first();
		$product->qty = $data->qty;
		$product->kinguinId = $data->kinguinId;
		$product->price = $data->price;
		$product->updated_at = $data->updatedAt;
		$product->cheapestOfferId = json_encode($data->cheapestOfferId);
		$product->save();

		ob_start();

		header("HTTP/1.1 204 NO CONTENT");

		header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
		header("Pragma: no-cache"); // HTTP 1.0.
		header("Expires: 0"); // Proxies.

		ob_end_flush(); //now the headers are sent
		exit;
	}

	public function search(Request $request) {
		$products = Product::whereLike('name', "%".$request->term."%")->orWhereLike('description', "%".$request->term."%")->whereNotLike('name', "%Kinguin%")->select('coverImage', 'id', 'name', 'qty', 'price', 'platform')->get();

		$output = array();
		if(count($products) > 0)
		{
		foreach($products as $product)
		{
		if (strpos(strtolower($product->name), 'kinguin') !== false) {
			continue;	
		}
		$html = "<table style='width:100%;'>";
		$html .= "<tr>";
		$html .= "<td width='70'>";
		$html .= '<img src="'.$product->coverImage.'" width="50" />';
		$html .= "</td>";
		$html .= "<td>";
		$html .= "<table>";
		$html .= "<tr>";
		$html .= "<td>";
		$html .= $product->name;
		$html .= "</td>";
		$html .= "</tr>";
		$html .= "<tr>";
		$html .= "<td>";
		$html .= $product->platform;
		$html .= "</td>";
		$html .= "</tr>";
		$html .= "</table>";
		$html .= "</td>";
		$html .= "</tr>";
		$html .= "</table>";
		$temp_array = array();
		$temp_array['id'] = $product->id;
		$temp_array['value'] = $product->name;
		$temp_array['label'] = $html;
		$output[] = $temp_array;
		}
		}
		else
		{
		$temp_array['id'] = '';
		$output['value'] = '';
		$output['label'] = 'No Record Found';
		}

		echo json_encode($output);
		exit;
	}

	public function syncProductImages(Request $request) {
		$products = Product::SelectSet(['kinguinId'])->whereNull('coverImage')->get(200);
		$count = 0;
		foreach($products as $product) {

			$kproduct = getProduct($product->kinguinId);
			$kproduct = json_decode($kproduct);
			
			$product->name = $kproduct->name ?? null;
			$product->description = $kproduct->description ?? null;
			$product->coverImage = $kproduct->coverImage ?? $kproduct->images->cover->thumbnail ?? '';
			$product->coverImageOriginal = $kproduct->coverImageOriginal ?? $kproduct->images->cover->url ?? '';
			if(empty($product->coverImage)) {
				if(!empty($kproduct->images->screenshots[0])) {
					$product->coverImage = $kproduct->images->screenshots[0]->thumbnail;
				}
			}
			if(empty($product->coverImageOriginal)) {
				if(!empty($kproduct->images->screenshots[0])) {
					$product->coverImageOriginal = $kproduct->images->screenshots[0]->url;
				}
			}
			$product->platform = $kproduct->platform ?? null;
			$product->releaseDate = $kproduct->releaseDate ?? null;
			$product->qty = $kproduct->qty ?? null;
			$product->textQty = $kproduct->textQty ?? null;
			$product->price = $kproduct->price ?? null;
			$product->regionalLimitations = $kproduct->regionalLimitations ?? null;
			$product->regionId = $kproduct->regionId ?? null;
			$product->activationDetails = $kproduct->activationDetails ?? null;
			$product->productId = $kproduct->productId ?? null;
			$product->originalName = $kproduct->originalName ?? null;
			$product->offersCount = $kproduct->offersCount ?? null;
			$product->totalQty = $kproduct->totalQty ?? null;
			$product->ageRating = $kproduct->ageRating ?? null;
			$product->steam = $kproduct->steam ?? null;
			$product->cheapestOfferId = $kproduct->cheapestOfferId ? json_encode($kproduct->cheapestOfferId) :  null;
			$product->languages = $kproduct->languages ? json_encode($kproduct->languages) :  null;
			$product->tags = $kproduct->tags ? json_encode($kproduct->tags) :  null;
			$product->merchantName = $kproduct->merchantName ?? null ? json_encode($kproduct->merchantName) :  null;
			$product->developers = $kproduct->developers ?? null ? json_encode($kproduct->developers) :  null;
			$product->publishers = $kproduct->publishers ?? null ? json_encode($kproduct->publishers) :  null;
			$product->genres = $kproduct->genres  ?? null ? json_encode($kproduct->genres) :  null;
			$product->updated_at = date('y-m-d h:i:s');
			$product->save();

			
			if(!empty($kproduct->images->screenshots)){
				ProductScreenshot::where('product_id', $product->id)->delete();
				foreach($kproduct->images->screenshots as $screenshot){
					$product_screenshot = new ProductScreenshot();
					$product_screenshot->product_id  = $product->id;
					$product_screenshot->url  = $screenshot->thumbnail;
					$product_screenshot->url_original  = $screenshot->url;
					$product_screenshot->save();
				}
			}

			$count++;

		}
		echo $count. " products images updated";
		exit();
	}

	public function regionCountries(Request $request) {
		$region = $request->region;
		return getRegionCountries($region);
	}

	public function updateCurrencyRate() {
		file_put_contents('currency_rates_api_executed.txt', 1);
		$currencyRates = CurrencyRate::get();
		foreach($currencyRates as $currencyRate):
			$base_price = callCurrencyApi($currencyRate->from, $currencyRate->to, 1);
			saveCurrencyrate($currencyRate->from, $currencyRate->to, $base_price);
		endforeach;

		die('Currency Rates Updated.');
	}
}