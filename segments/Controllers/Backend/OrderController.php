<?php

namespace Controllers\Backend;

use Bones\Request;
use Models\City;
use Models\Order;
use Models\OrderItem;

class OrderController
{
	public function index(Request $request) {
		
        $orders = Order::orderBy('id')->get();
		return render('backend/orders/index', [
			'orders' => $orders
		]);
	}

    public function view(Request $request, Order $order) {
		$order_items = OrderItem::where('order_id', $order->id)->get();
		return render('backend/orders/view', [
			'order' => $order,
			'orderItems' => $order_items
		]);
	}
}
