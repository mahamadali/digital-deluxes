<?php

namespace Controllers\Backend;

use Bones\Request;
use Models\City;
use Models\CustomerBillingInfo;
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
		$customer_billing_infos = CustomerBillingInfo::where('order_reference', $order->reference)->first();
		return render('backend/orders/view', [
			'order' => $order,
			'orderItems' => $order_items,
			'customer_billing_infos' => $customer_billing_infos
		]);
	}

	public function delete(Request $request)
	{
		
		OrderItem::whereIn('order_id',$request->orderIds)->delete();
		Order::whereIn('id',$request->orderIds)->delete();
		return response()->json(['stauts'=> 'success', 'msg' => 'Orders deleted successfully']);
	}
}
