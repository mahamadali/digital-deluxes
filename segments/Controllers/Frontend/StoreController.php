<?php

namespace Controllers\Frontend;


use Bones\Request;
use Bones\Session;
use Models\Product;

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
		$price = $request->get("price") ?? '';

        if($name){
			$products = $products->where('name', '%'.$name.'%', 'LIKE');
        }

		if($price){
            $products = $products->where('price', $price);
        }

		$product_limit = 10;
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

}