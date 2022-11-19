<?php

namespace Controllers\Frontend;

use Bones\Request;
use Bones\Session;
use Models\CustomerBillingInfo;
use Models\Order;

class OrderController
{
    public function index(Request $request) {
		$orders = Order::where('user_id', auth()->id)->orderBy('id', 'DESC')->get();
		return render('frontend/orders/index', [
			'orders' => $orders
		]);
	}

    public function view(Request $request, Order $order) {
		$customer_billing_infos = CustomerBillingInfo::where('order_reference', $order->reference)->first();
		return render('frontend/orders/view', [
			'order' => $order,
			'customer_billing_infos' => $customer_billing_infos
		]);
	}
    
    
}