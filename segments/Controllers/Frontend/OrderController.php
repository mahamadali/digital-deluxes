<?php

namespace Controllers\Frontend;

use Bones\Request;
use Bones\Session;
use Models\Order;

class OrderController
{
    public function index(Request $request) {
		
		return render('frontend/orders/index', [
			'orders' => user()->orders
		]);
	}

    public function view(Request $request, Order $order) {
		
		return render('frontend/orders/view', [
			'order' => $order
		]);
	}
    
    
}