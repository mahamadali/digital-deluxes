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
			$cart->product_price = remove_format($product->price);
			$cart->product_qty = 1;
			$checkItemAvailableInstore = getProduct($product->kinguinId);
			$kinguinproduct = json_decode($checkItemAvailableInstore);

			if(!empty($product->productId)){
				if(isset($kinguinproduct->qty) && $kinguinproduct->qty >= 1) {
					$cart->save();
				} else {
						return redirect()->withFlashError('Sorry! Game is not available in store')->with('old', $request->all())->back();
				}

			}else{
				$cart->save();
			}	


			
			
		}else{
			$is_cart_exist->product_qty = $is_cart_exist->product_qty + 1;
			$checkItemAvailableInstore = getProduct($product->kinguinId);
			$kinguinproduct = json_decode($checkItemAvailableInstore);


			if(!empty($product->productId)){
				if(isset($kinguinproduct->qty) && $kinguinproduct->qty >= ($is_cart_exist->product_qty + 1)) {
					$is_cart_exist->save();
				} else {
						return redirect()->withFlashError('Sorry! Game is not available in store')->with('old', $request->all())->back();
				}
			}else{
				$is_cart_exist->save();
			}	

			
			
		}

		return redirect()->withFlashSuccess('Item added into cart successfully!')->with('addedcart', 1)->back();
	}

	public function removeToCart(Request $request,$cart_id)
	{
		Cart::where('id',$cart_id)->where('user_id',auth()->id)->delete();

		return redirect()->withFlashSuccess('Product removed from cart successfully!')->with('addedcart', 1)->back();
		
	}

	public function updateQty(Request $request, Product $product)
	{
		$is_cart_exist = Cart::where('product_id', $product->id)->where('user_id', auth()->id)->first();
		$product = Product::where('id',$product_id)->first();
		
		if(!empty($is_cart_exist)){
			$is_cart_exist->product_qty = $request->qty;
			$checkItemAvailableInstore = getProduct($product->kinguinId);
			$kinguinproduct = json_decode($checkItemAvailableInstore);

			if(!empty($product->productId)){
				if(isset($kinguinproduct->qty) && $kinguinproduct->qty >= $request->qty) {
					$is_cart_exist->save();
				} else {
					return response()->json(['status' => 304, 'message' => 'Qty not available in store!']);
				}
			}else{
				$is_cart_exist->save();
			}	

			
			
		}

		return response()->json(['status' => 200, 'message' => 'Cart updated!']);
	}
}