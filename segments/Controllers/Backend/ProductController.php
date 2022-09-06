<?php

namespace Controllers\Backend;

use Bones\Request;
use Models\Product;
use Models\ProductScreenshot;
use Models\ProductVideo;
use Models\ProductSystemRequirement;



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

	public function create()
	{
		return render('backend/products/create');
	}

	public function store(Request $request)
	{
		
		$validator = $request->validate([
			'name' => 'required|min:2|max:30',
			'coverImage' => 'required',
			'coverImageOriginal' => 'required',
			'qty' => 'required',
			'textQty' => 'required',
			'price' => 'required',
			'totalQty' => 'required',
		]);

		if ($validator->hasError()) {
			return redirect()->withFlashError(implode('<br>', $validator->errors()))->with('old', $request->all())->back();
		}

		$productData = $request->getOnly(['name','coverImage','coverImageOriginal','qty','textQty','price','totalQty']);

		$productData['tags'] = $request->tags ? json_encode(explode(',',$request->tags)) :  null;
		$productData['merchantName'] = $request->merchantName ?? null ? json_encode(explode(',',$request->merchantName)) :  null;
		$productData['developers'] = $request->developers ?? null ? json_encode(explode(',',$request->developers)) :  null;
		$productData['publishers'] = $request->publishers ?? null ? json_encode(explode(',',$request->publishers)) :  null;
		$productData['genres'] = $request->genres  ?? null ? json_encode(explode(',',$request->genres)) :  null;
		$productData['description'] = $request->description ??  null;
		$productData['platform'] = $request->platform ??  null;
		$productData['releaseDate'] = $request->releaseDate ? $request->releaseDate :  null;
		$productData['activationDetails'] = $request->activationDetails ??  null;
		$productData['originalName'] = $request->originalName ? $request->originalName : null;
		$productData['order_type'] = 'M';

		$product = Product::create($productData);
		
		if(count($request->screenshot_url) != 0){
			foreach($request->screenshot_url as $key => $screenshot){
				$product_screenshot = new ProductScreenshot();
				$product_screenshot->product_id = $product->id;
				$product_screenshot->url = $request->screenshot_url[$key];
				$product_screenshot->url_original = $request->screenshot_url_original[$key];
				$product_screenshot->save();
			}
		}


		if(count($request->video_id) != 0){
			foreach($request->video_id as $key => $video){
				$product_video = new ProductVideo();
				$product_video->product_id = $product->id;
				$product_video->name = $request->video_name[$key];
				$product_video->video_id = $request->video_id[$key];
				$product_video->save();
			}
		}


		if(count($request->system) != 0){
			foreach($request->system as $key => $system){
				$product_system_requirement = new ProductSystemRequirement();
				$product_system_requirement->product_id = $product->id;
				$product_system_requirement->system = $request->system[$key];
				$product_system_requirement->requirement = $request->requirement[$key];
				$product_system_requirement->save();
			}
		}


		if (!empty($product)) {
			return redirect(route('admin.products.index'))->withFlashSuccess('Product created successfully!')->go();
		} else {
			return redirect()->withFlashError('Something went wrong!')->back();
		}
	}



    public function view(Request $request, Product $product) {
		
		return render('backend/products/view', [
			'product' => $product
		]);
	}
}
