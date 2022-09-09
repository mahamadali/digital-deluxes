<?php

namespace Controllers\Frontend;

use Bones\Alert;
use Bones\Request;
use Mail\KeysEmail;
use Mail\NewOrderAdminEmail;
use Models\Cart;
use Models\Country;
use Models\CustomerBillingInfo;
use Models\GameKey;
use Models\Order;
use Models\OrderItem;
use Models\PaymentMethod;
use Models\ProductKeys;
use Models\UserPaymentMethod;
use Omnipay\Omnipay;

class CheckoutController
{

	public function index(Request $request)
	{
		$cartTotal = cartTotalOriginal();
		
		if(empty($cartTotal)) {
			return redirect(route('frontend.store.list'))->withFlashError('No items in cart')->go();
		}
		
		$total_amount = currencyConverter('EUR', "COP", $cartTotal);
		
		$wallet_in_cop = currencyConverter('EUR', 'COP', user()->wallet_amount);
		if($wallet_in_cop > $total_amount) {
			$walletEnable = true;
		} else {
			$walletEnable = false;
		}
		
		$order_reference = strtoupper(random_strings(12));
        $user = user();
        $countries = Country::get();
		$user_active_payments = UserPaymentMethod::where('user_id', auth()->id)->where('status', 1)->pluck('payment_method_id');
		
		if(empty($user_active_payments)) {
			$payment_methods = PaymentMethod::where('status', 'ACTIVE')->where('type', 'both')->get();
		} else {
			$user_active_payments = array_map(function($element) {
				return $element->payment_method_id;
			},$user_active_payments);
			$payment_methods = PaymentMethod::where('status', 'ACTIVE')->whereIn('id', $user_active_payments)->where('type', 'both')->get();
		}
		
		return render('frontend/checkout/index', ['countries' => $countries, 'user' => $user, 'payment_methods' =>  $payment_methods, 'total_amount' => $total_amount, 'order_reference' => $order_reference, 'walletEnable' => $walletEnable, 'wallet_in_cop' => $wallet_in_cop]);
	}

	public function createOrder(Request $request) {

		CustomerBillingInfo::where('user_id', auth()->id)->where('order_reference', $request->order_reference)->delete();
		$customerBillingInfo = new CustomerBillingInfo();
		$customerBillingInfo->user_id = auth()->id;
		$customerBillingInfo->order_reference = $request->order_reference;
		$customerBillingInfo->first_name = $request->first_name;
		$customerBillingInfo->last_name = $request->last_name;
		$customerBillingInfo->email = $request->email;
		$customerBillingInfo->phone_number = $request->country_code.str_replace(" ", "", $request->phone);
		$customerBillingInfo->address = $request->address;
		$customerBillingInfo->country = $request->country;
		$customerBillingInfo->city = $request->city;
		$customerBillingInfo->additional_note = $request->additional_note;
		$customerBillingInfo->save();

		$paymentMethod = PaymentMethod::find($request->payment_method);
		if(!empty($paymentMethod) && $paymentMethod->title == 'Mercado Pago') {
			
			$cartTotal = cartTotalOriginal();
			$total_amount = currencyConverter('EUR', "COP", $cartTotal);
			
			\MercadoPago\SDK::setAccessToken(setting('mercadopago.access_token'));
			$preference = new \MercadoPago\Preference();
			
			$item = new \MercadoPago\Item();
			$item->title = 'Order for '.setting('app.title');
			$item->quantity = 1;
			$item->unit_price = (int) $total_amount;

			$preference->items = array($item);

			$preference->notification_url = 'https://dev.wisencode.com/webhook/mercadopago/notify.php';

			$preference->back_urls = array(
				"success" => route('frontend.payment.mercadopago-order.success').'?dd_payment_method_id='.$paymentMethod->id.'&amount='.$total_amount.'&order_reference='.$request->order_reference,
				"failure" => route('frontend.payment.mercadopago-order.failure'), 
				"pending" => route('frontend.payment.mercadopago-order.pending')

				// "success" => 'https://dev.wisencode.com/webhook/mercadopago/success.php?dd_payment_method_id='.$paymentMethod->id.'&amount='.$total_amount,
				// "failure" => 'https://dev.wisencode.com/webhook/mercadopago/failure.php',
				// "pending" => 'https://dev.wisencode.com/webhook/mercadopago/pending.php'

				
			);
			$preference->auto_return = "approved"; 

			$preference->save();

			$response = array(
				'status' => 200,
				'preference_id' => $preference->id,
				'redirectUrl' => setting('mercadopago.sandbox') ? $preference->sandbox_init_point : $preference->init_point,
				'payment_method' => $paymentMethod->title
			);
			return response()->json($response);
		}

		if($request->payment_method == 'Wallet') {

			$cartTotal = cartTotalOriginal();
			$total_amount = currencyConverter('EUR', "COP", $cartTotal);

			$user = user();
			
			$cartItems = cartItems($user->id);

			
			$wallet_in_cop = currencyConverter('EUR', 'COP', $user->wallet_amount);
			if($wallet_in_cop < $total_amount) {
				return response()->json(['status' => 304, 'message' => 'Insufficient funds in wallet!']);
			}
			$remaning_cop = $wallet_in_cop - $total_amount;
			$remaining_in_eur = currencyConverter('COP', 'EUR', $remaning_cop);
			
			$manualOrderItems = [];
			$manualOrderTotalPrice = 0;
			$kinguinOrderItems = [];
			$kinguinOrderTotalPrice = 0;
			foreach($cartItems as $item) {
				if($item->product()->product_type == 'M') {
					$manualOrderItems[] = $item;
					$manualOrderTotalPrice += currencyConverter('EUR', 'COP', $item->product()->price_original);
				}
				if($item->product()->product_type == 'K') {
					$kinguinOrderItems[] = $item;
					$kinguinOrderTotalPrice += currencyConverter('EUR', 'COP', $item->product()->price_original);
				}
			}

			
			
			if(!empty($manualOrderItems)) {
				$mannual_order = new Order();
				$mannual_order->reference = $request->order_reference;
				$mannual_order->transaction_id = "WALLET-".$request->order_reference;
				$mannual_order->payment_method_type = 'Wallet';
				$mannual_order->payment_method = 'Wallet';
				$mannual_order->status = 'APPROVED';
				$mannual_order->status_message = NULL;
				$mannual_order->currency = 'COP';
				$mannual_order->amount_in_cents = $manualOrderTotalPrice * 100;
				$mannual_order->order_amount = $manualOrderTotalPrice;
				$mannual_order->user_id = auth()->id;
				$mannual_order->order_type = 'M';
				$mannual_order = $mannual_order->save();
				foreach($manualOrderItems as $manualOrderItems) {
					$mannual_order_item = new OrderItem();
					$mannual_order_item->order_id = $mannual_order->id;
					$mannual_order_item->product_id = $manualOrderItems->product_id;
					$mannual_order_item->product_name = $manualOrderItems->product_name;
					$mannual_order_item->product_price = currencyConverter('EUR', 'COP', $manualOrderItems->product_price);
					$mannual_order_item->product_price_profit = getProfitCommission(remove_format($manualOrderItems->product()->price_original));
					$mannual_order_item->product_qty = $manualOrderItems->product_qty;
					$mannual_order_item->save();

					$productKeys = ProductKeys::where('product_id', $manualOrderItems->product_id)->where('is_used', 0)->get($manualOrderItems->product_qty);

					if(!empty($productKeys)) {
						foreach($productKeys as $productKey) {
							$game_key = new GameKey();
							$game_key->order_id = $mannual_order->id;
							$game_key->product_id = $manualOrderItems->product_id;
							$game_key->serial = $productKey->key;
							$game_key->type = 'text/plain';
							$game_key->name = $manualOrderItems->product()->name;
							$game_key->kinguinId = NULL;
							$game_key->offerId = NULL;
							$game_key->save();

							$productKey->is_used = 1;
							$productKey->save();
						}
					}
				}

				$order = Order::find($mannual_order->id);
				Alert::as(new KeysEmail($order))->notify();
				Alert::as(new NewOrderAdminEmail($order))->notify();
			}

			
			if(!empty($kinguinOrderItems)) {
				$order = new Order();
				$order->reference = $request->order_reference;
				$order->transaction_id = "WALLET-".$request->order_reference;
				$order->payment_method_type = 'Wallet';
				$order->payment_method = 'Wallet';
				$order->status = 'APPROVED';
				$order->status_message = NULL;
				$order->currency = 'COP';
				$order->amount_in_cents = $kinguinOrderTotalPrice * 100;
				$order->order_amount = $kinguinOrderTotalPrice;
				$order->user_id = auth()->id;
				$order->order_type = 'K';
				$order = $order->save();
				foreach($kinguinOrderItems as $kinguinOrderItem) {
					$orderItem = new OrderItem();
					$orderItem->order_id = $order->id;
					$orderItem->product_id = $kinguinOrderItem->product_id;
					$orderItem->product_name = $kinguinOrderItem->product_name;
					$orderItem->product_price = currencyConverter('EUR', 'COP', $kinguinOrderItem->product_price);
					$orderItem->product_price_profit = getProfitCommission(remove_format($kinguinOrderItem->product()->price_original));
					$orderItem->product_qty = $kinguinOrderItem->product_qty;
					$orderItem->save();
				}

				$orderProducts = OrderItem::where('order_id', $order->id)->get();
		
				$products = [];
				foreach($orderProducts as $orderProduct) {
					// $offerId = json_decode($orderProduct->product->cheapestOfferId)[0];
					// $keyTypeResponse = $this->fetchKeyType($orderProduct);
					// $keyTypeResponse = json_decode($keyTypeResponse);
					// $offerId = $this->fetchOfferId($keyTypeResponse);
					// $offer = json_decode($offerId);
					$products[] = (object) [
						'kinguinId' => $orderProduct->product->kinguinId,
						'qty' => $orderProduct->product_qty,
						'name' => $orderProduct->product_name,
						'price' => $orderProduct->product_price,
						// 'keyType' => 'text',
						// 'offerId' => $offerId,
					];
				}
				
				// $orderExternalId = $this->orderExternalId($keyTypeResponse);
				// $orderExternalId = json_decode($orderExternalId);
				$params = (object) [
					'products' => $products,
					'orderExternalId' => $order->reference
				];

				// dd(json_decode("{\"products\":[{\"kinguinId\":1949,\"qty\":1,\"name\":\"Counter-Strike: Source Steam CD Key\",\"price\":5.79}]}"));

				// dd($params);

				// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
				$ch = curl_init();

				curl_setopt($ch, CURLOPT_URL, setting('kinguin.endpoint').'/v1/order');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

				$headers = array();
				$headers[] = 'X-Api-Key: '.setting('kinguin.api_key');
				$headers[] = 'Content-Type: application/json';
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

				$result = curl_exec($ch);
				if (curl_errno($ch)) {
					echo 'Error:' . curl_error($ch);
				}
				curl_close($ch);
				file_put_contents('create-order.txt', $result);

				$orderData = json_decode($result);
				
				$order->kg_orderid = $orderData->orderId ?? '';
				$order->save();
			}

			Cart::where('user_id',$user->id)->delete();

			$user->wallet_amount = $remaining_in_eur;
			$user->save();

			return response()->json(['status' => 200, 'message' => 'Order placed successfully!', 'redirectUrl' => route('frontend.orders.index')]);


		}

		if(!empty($paymentMethod) && $paymentMethod->title == 'Paypal') {

			$cartTotal = cartTotalOriginal();
			$total_amount = currencyConverter('EUR', $paymentMethod->currency, $cartTotal);
			
			$gateway = Omnipay::create('PayPal_Rest');
			$gateway->setClientId(setting('paypal.CLIENT_ID'));
			$gateway->setSecret(setting('paypal.CLIENT_SECRET'));
			$gateway->setTestMode(setting('paypal.sandbox')); //set it to 'false' when go live
			try {
				$response = $gateway->purchase(array(
					'amount' => round($total_amount, 2),
					'description' => 'Order for Digital Deluxes',
					'currency' => setting('paypal.PAYPAL_CURRENCY'),
					'returnUrl' => route('frontend.payment.paypal-order.success', ['payment_method' => $paymentMethod->id, 'order_reference' => $request->order_reference]),
					'cancelUrl' => route('frontend.payment.paypal-order.cancel', ['payment_method' => $paymentMethod->id]),
				))->send();
		 
				if ($response->isRedirect()) {
					$redirectUrl = $response->getData()['links'][1]['href'];
					return response()->json(['status' => 200, 'redirectUrl' => $redirectUrl]);
					// $response->redirect(); // this will automatically forward the customer
				} else {
					// not successful
					echo $response->getMessage();
				}
			} catch(Exception $e) {
				echo $e->getMessage();
				exit;
			}
		}

		
	}
}