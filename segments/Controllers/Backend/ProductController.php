<?php

namespace Controllers\Backend;

use Bones\Request;
use Models\Product;

class ProductController
{
	public function index(Request $request) {
		
        $products = Product::orderBy('id')->get();
		return render('backend/products/index', [
			'products' => $products
		]);
	}

    public function view(Request $request, Product $product) {
		
		return render('backend/products/view', [
			'product' => $product
		]);
	}
}
