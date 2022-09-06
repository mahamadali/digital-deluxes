<?php

namespace Controllers\Frontend;

use Bones\Request;
use Models\Country;
use Models\CustomerBillingInfo;
use Models\PaymentMethod;

class CheckoutController
{

	public function index(Request $request)
	{
		if(strtoupper(session()->getCurrency()) != "COP") {
			$total_amount = currencyConverter(strtoupper(session()->getCurrency()), "COP", cartTotal());
		} else {
			$total_amount = cartTotal();
		}
		
		$order_reference = strtoupper(random_strings(12));
        $user = user();
        $countries = Country::get();
		$payment_methods = PaymentMethod::where('status', 'ACTIVE')->where('type', 'both')->get();
		return render('frontend/checkout/index', ['countries' => $countries, 'user' => $user, 'payment_methods' =>  $payment_methods, 'total_amount' => $total_amount, 'order_reference' => $order_reference]);
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
		if($paymentMethod->title == 'Mercado Pago') {

			if(strtoupper(session()->getCurrency()) != "COP") {
				$total_amount = currencyConverter(strtoupper(session()->getCurrency()), "COP", cartTotal());
			} else {
				$total_amount = cartTotal();
			}
			// dd($total_amount);
			
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

		
	}
}