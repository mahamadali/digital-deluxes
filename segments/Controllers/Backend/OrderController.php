<?php

namespace Controllers\Backend;

use Bones\Request;
use Models\City;
use Models\Order;

class OrderController
{
	public function index(Request $request) {
		
        $orders = Order::orderBy('id')->get();
		return render('backend/orders/index', [
			'orders' => $orders
		]);
	}

    public function view(Request $request, Order $order) {
		
		return render('backend/orders/view', [
			'order' => $order
		]);
	}
}
