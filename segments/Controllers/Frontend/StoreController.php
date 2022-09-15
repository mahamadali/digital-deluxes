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
		$operatingSystems = ProductSystemRequirement::selectSet(['system'])->groupBy('system')->orderBy('id','ASC')->get();
		
        $products = Product::whereNotLike('platform', 'kinguin')->whereNotLike('name', '%Kinguin%');
		
        $name = $request->get("name") ?? '';
		$category = $request->get("category") ?? '';
		$min_price = $request->get("min_price") ?? '';
		$max_price = $request->get("max_price") ?? '';
		$system = $request->get("system") ?? '';
		$language = $request->get("language") ?? '';
		$genre = $request->get("genre") ?? '';
		$sort_by = $request->get("sort_by") ?? '';

        if($sort_by == 'Price Lowest') {
			$products->orderBy('CAST(price AS DECIMAL(10,2))', 'ASC');
		} else if($sort_by == 'Price Highest') {
			$products->orderBy('CAST(price AS DECIMAL(10,2))', 'DESC');
		} else if($sort_by == 'Newest') {
			$products->orderBy('id', 'DESC');
		} else {
			$products->orderBy('id', 'ASC');
		}

		if($system){
			$productIds = ProductSystemRequirement::where('`system`', $system)->pluck('product_id');
			$productIds = array_map(function($element) {
				return $element->product_id;
			},$productIds);
            $products = $products->whereIn('id', $productIds);
        }

		if($category){
			$products = $products->where('platform', '%'.$category.'%', 'LIKE');
        }

		if($language){
			$products = $products->whereLike('languages', "%$language%");
        }

		if($genre){
			$products = $products->whereLike('genres', "%$genre%");
        }

		if($name){
			$products = $products->where('name', '%'.$name.'%', 'LIKE');
			// $products = $products->where('MATCH(name) AGAINST ("'.$name.'" IN NATURAL LANGUAGE MODE)');
        }

		if($min_price){
            $products = $products->where('price', (int) $min_price, '>=');
        }

		if($max_price){
            $products = $products->where('price', (int) $max_price, '<=');
        }

		$product_limit = 12;
        $products = $products->paginate(12);

		// echo \Bones\Database::getLastQuery();
		// exit;
		
        return render('frontend/store/index', [
			'products' => $products,
			'product_limit' => $product_limit,
			'category' => $category,
			'operatingSystems' => $operatingSystems
		]);
		
	}

    public function view(Request $request, Product $product)
	{
		if(empty($product->price))
			error('404');

		if($product->product_type == 'M'):
			$productQty = count($product->manual_keys()->where('is_used', 0)->get());
		endif;
		if($product->product_type == 'K'):
			$productQty = $product->qty;
		endif;
		$platformLogos = PlatformLogo::where('platform', $product->platform)->get();
		return render('frontend/store/view_product', [
			'product' => $product,
			'platformLogos' => $platformLogos,
			'productQty' => $productQty
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