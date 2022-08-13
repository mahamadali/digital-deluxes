<?php

namespace Controllers\Frontend;

use Bones\Request;
use Models\Cart;
use Models\Product;

class CartController
{

	public function index(Request $request)
	{
		$cart_details  = Cart::where('user_id',auth()->id)->get();

		return render('frontend/cart/index', [
			'cart_details' => $cart_details
		]);
	}

	public function addToCart(Request $request,$product_id)
	{
		$is_cart_exist = Cart::where('product_id',$product_id)->where('user_id',auth()->id)->first();

		$product = Product::where('id',$product_id)->first();

		if(empty($is_cart_exist)){
			$cart = new Cart();
			$cart->user_id = auth()->id;
			$cart->product_id = $product_id;
			$cart->product_name = $product->name;
			$cart->product_price = $product->price;
			$cart->product_qty = 1;
			$cart->save();
		}else{
			$is_cart_exist->product_qty = $is_cart_exist->product_qty + 1;
			$is_cart_exist->save();
		}

		return redirect()->withFlashSuccess('Add To Cart added successfully!')->with('old', $request->all())->back();
	}

	public function removeToCart(Request $request,$cart_id)
	{
		Cart::where('id',$cart_id)->where('user_id',auth()->id)->delete();

		return redirect()->withFlashSuccess('Product removed from cart successfully!')->with('old', $request->all())->back();
		
	}

	public function updateQty(Request $request, Product $product)
	{
		$is_cart_exist = Cart::where('product_id', $product->id)->where('user_id', auth()->id)->first();
		
		if(!empty($is_cart_exist)){
			$is_cart_exist->product_qty = $request->qty;
			$is_cart_exist->save();
		}

		return response()->json(['status' => 200, 'message' => 'Cart updated!']);
	}
}