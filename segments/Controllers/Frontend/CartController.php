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
			$cart->product_price = remove_format($product->price_original);
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
				if($product->product_type == 'M') {
					$productQty = count($product->manual_keys()->where('is_used', 0)->get());
					if(isset($productQty) && $productQty > 0) {
						$cart->save();
					} else {
						return redirect()->withFlashError('Sorry! Game qty is not available in store')->with('old', $request->all())->back();
					}
					
				}
				
			}	


			
			
		}else{
			if($product->product_type == 'K') {
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

			if($product->product_type == 'M') {
				$is_cart_exist->product_qty = $is_cart_exist->product_qty + 1;
				$productQty = count($product->manual_keys()->where('is_used', 0)->get());
				if(isset($productQty) && $productQty >= $is_cart_exist->product_qty + 1) {
					$is_cart_exist->save();
				} else {
					return redirect()->withFlashError('Sorry! Game qty not available in store')->with('old', $request->all())->back();
				}
			}

			
			
		}

		return redirect()->withFlashSuccess('Item added into cart successfully!')->with('addedcart', 1)->back();
	}

	public function removeToCart(Request $request,$cart_id)
	{
		Cart::where('id',$cart_id)->where('user_id',auth()->id)->delete();
		
		if(session()->has('order_coupon')) {
			session()->remove('order_coupon');
		}
		
		return redirect()->withFlashSuccess('Product removed from cart successfully!')->with('addedcart', 1)->back();
		
	}

	public function updateQty(Request $request, Product $product)
	{
		if($request->has('qty') && $request->has('qty') <= 0) {
			return response()->json(['status' => 304, 'message' => 'Qty should not empty!']); 
		}
		$is_cart_exist = Cart::where('product_id', $product->id)->where('user_id', auth()->id)->first();
		$product = Product::where('id',$product->id)->first();
		
		if(!empty($is_cart_exist)){

			if($product->product_type == 'K') {
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

			if($product->product_type == 'M') {
				$productQty = count($product->manual_keys()->where('is_used', 0)->get());
				$is_cart_exist->product_qty = $request->qty;
				if(isset($productQty) && $productQty >= $request->qty) {
					$is_cart_exist->save();
				} else {
					return response()->json(['status' => 304, 'message' => 'Qty not available in store!']);
				}
			}
			
		}

		return response()->json(['status' => 200, 'message' => 'Cart updated!']);
	}
}