<?php

namespace Controllers\Frontend;


use Bones\Request;
use Bones\Session;
use Models\Product;
use Models\UserWishlist;

class StoreController
{
	public function index(Request $request)
	{
		$page = $request->get("page");
		if ($page == "") {
			$page = 1;
		}

		
        $products = Product::orderBy('id','ASC');

        $name = $request->get("name") ?? '';
		$min_price = $request->get("min_price") ?? '';
		$max_price = $request->get("max_price") ?? '';

        if($name){
			$products = $products->where('name', '%'.$name.'%', 'LIKE');
        }

		

		if($min_price){
            $products = $products->where('price', (int) $min_price, '>=');
        }

		if($max_price){
            $products = $products->where('price', (int) $max_price, '<=');
        }

		$product_limit = 12;
        $products = $products->paginate($product_limit, $page);

        return render('frontend/store/index', [
			'products' => $products,
			'product_limit' => $product_limit
		]);
		
	}

    public function view(Request $request, Product $product)
	{
		return render('frontend/store/view_product', [
			'product' => $product
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