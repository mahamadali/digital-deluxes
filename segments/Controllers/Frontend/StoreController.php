<?php

namespace Controllers\Frontend;


use Bones\Request;
use Bones\Session;
use Google\Service\VersionHistory\Platform;
use Models\PlatformLogo;
use Models\Product;
use Models\ProductSystemRequirement;
use Models\UserWishlist;

class StoreController
{
	public function index(Request $request)
	{
		\Bones\Database::keepQueryLog();

		$operatingSystems = ProductSystemRequirement::select(['system'])->groupBy('system')->orderBy('id','ASC')->get();
		
        $products = Product::whereNotLike('platform', 'kinguin')->whereNotLike('name', '%Kinguin%')->whereNotNull('price')->whereNotNull('qty')->where('qty', '>', 0);
		
        $name = $request->get("name") ?? '';
		$category = $request->get("category") ?? '';
		$min_price = $request->get("min_price") ?? '';
		$max_price = $request->get("max_price") ?? '';
		$system = $request->get("system") ?? '';
		$language = $request->get("language") ?? '';
		$genre = $request->get("genre") ?? '';
		$sort_by = $request->get("sort_by") ?? '';

        if($sort_by == 'Price Lowest') {
			$products->orderByRaw('CAST(price AS DECIMAL(10,2)) ASC');
		} else if($sort_by == 'Price Highest') {
			$products->orderByRaw('CAST(price AS DECIMAL(10,2)) DESC');
		} else if($sort_by == 'Newest') {
			$products->orderBy('id', 'DESC');
		} else {
			$products->orderByRaw('CASE 
			WHEN regionalLimitations="Region free" THEN rand() 
			WHEN regionalLimitations="Other" THEN rand() 
		END DESC');
		}

		if($system){
			$productIds = ProductSystemRequirement::where('system', $system)->pluck('product_id');
			$productIds = array_map(function($element) {
				return $element;
			},$productIds);
            $products->whereRaw('id IN (?)', $productIds);
        }

		if($category){
			// if($category == 'PSN Card')
			// 	$products->whereLike('genres', '%PSN Card%');
			// else
				$products->whereLike('platform', '%'.$category.'%');
        }

		if($language){
			$products->whereLike('languages', "%$language%");
        }

		if($genre){
			$products->whereLike('genres', "%$genre%");
        }

		if($name){
			if(count(explode(" ", $name)) > 1) {
				$conc_string = '';
				foreach(explode(" ", $name) as $namePart) {
					$conc_string .= '%';
					$conc_string .= $namePart;
					$conc_string .= '%';
				}
				$products->whereLike('name', $conc_string);
			} else {
				$products->whereLike('name', '%'.$name.'%');
			}
			
			// $products = $products->where('MATCH(name) AGAINST ("'.$name.'" IN NATURAL LANGUAGE MODE)');
        }

		if($min_price){
            $products->whereRaw('CAST(price AS UNSIGNED) >= ?' , [ $min_price ]);
        }

		if($max_price){
            $products->whereRaw('CAST(price AS UNSIGNED) <= ?', [ $max_price ]);
        }

		$product_limit = 12;
        $products = $products->paginate($product_limit);

		//dd(\Bones\Database::getQueryLog());
		
        return render('frontend/store/index', [
			'products' => $products,
			'product_limit' => $product_limit,
			'category' => $category,
			'operatingSystems' => $operatingSystems
		]);
		
	}

    public function view(Request $request, Product $product, $slug)
	{
		$forspecificregion = findForRegion($slug);
		if(empty($product->price) || empty($product->qty))
			error('404');

		if($product->product_type == 'M'):
			$productQty = $product->manual_keys()->where('is_used', 0)->count();
		endif;
		if($product->product_type == 'K'):
			$productQty = $product->qty;
		endif;
		
		$platformLogos = PlatformLogo::where('platform', $product->platform)->get();
		return render('frontend/store/view_product', [
			'product' => $product,
			'platformLogos' => $platformLogos,
			'productQty' => $productQty,
			'forspecificregion' => $forspecificregion
		]);
	}

	public function addToFav(Request $request, Product $product) {
		$wishlist = UserWishlist::where('user_id', auth()->id)->where('product_id', $product->id)->first();
		if(!empty($wishlist)) {
			return response()->json(['status' => 304, 'message' => 'Already in wishlist']);
		}

		$wishlist = new UserWishlist();
		$wishlist->user_id = auth()->id;
		$wishlist->product_id = $product->id;
		$wishlist->save();

		return response()->json(['status' => 200, 'message' => 'Product added into your wishlists']);

	}

	public function RemoveFromFav(Request $request, Product $product) {
		$wishlist = UserWishlist::where('user_id', auth()->id)->where('product_id', $product->id)->delete();

		return response()->json(['status' => 200, 'message' => 'Product removed from your wishlists']);

	}

}