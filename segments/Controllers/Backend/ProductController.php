<?php

namespace Controllers\Backend;

use Bones\Request;
use Models\Product;

class ProductController
{
	public function index(Request $request) {
		$search = isset($_GET['search']) ? $_GET['search'] : '';
		if($search) {
			$products = Product::whereLike('name', '%'.$search.'%')->orderBy('id')->paginate(10);	
		} else {
			$products = Product::orderBy('id')->paginate(10);
		}
        
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
