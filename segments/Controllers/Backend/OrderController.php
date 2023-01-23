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

	public function resend(Request $request, Order $order) {
		if($order->status == 'APPROVED' && $order->order_type == 'K' && empty($order->kg_orderid)) {
			$orderProducts = OrderItem::where('order_id', $order->id)->get();
			$products = [];
			foreach($orderProducts as $orderProduct) {
				$offerId = $this->findCheepestOfferId($orderProduct->product->kinguinId);
				
				$products[] = (object) [
					'kinguinId' => $orderProduct->product->kinguinId,
					'qty' => $orderProduct->product_qty,
					'name' => $orderProduct->product_name,
					'price' => $orderProduct->product->price_original_value,
					'offerId' => $offerId,
				];
			}

			$params = (object) [
				'products' => $products,
				'orderExternalId' => $order->reference
			];

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, setting('kinguin.endpoint').'/v1/order');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

			$headers = array();
			$headers[] = 'X-Api-Key: '.setting('kinguin.api_key');
			$headers[] = 'Content-Type: application/json';
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$order_result = curl_exec($ch);
			
			if (curl_errno($ch)) {
				echo 'Error:' . curl_error($ch);
			}
			curl_close($ch);
			file_put_contents('create-order.txt', $order_result);

			$orderData = json_decode($order_result);

			if(isset($orderData->orderId)) {
				$order->kg_orderid = $orderData->orderId ?? '';
				$order->save();
				return redirect()->withFlashSuccess('Order sent successfully to kinguin!')->back();
			} else {
				$message = !empty($orderData->title) ? "Error: ".$orderData->title : '';
				return redirect()->withFlashError('Something went wrong to place order on kinguin! '. $message)->back();
			}
			
		} else {
			return redirect()->withFlashError('Order already placed on kinguin before!')->back();
		}
	}

	public function findCheepestOfferId($kinguinId) {
        $product_offers = json_decode(getProduct($kinguinId), true)['offers'] ?? [];
		if(!empty($product_offers)) {
            $prices = array_column($product_offers, 'price');
            $min_offer = $product_offers[array_search(min($prices), $prices)];
            return $min_offer['offerId'];
        } else {
            return '';
        }
    }
}
