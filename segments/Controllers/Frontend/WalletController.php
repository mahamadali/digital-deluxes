<?php

namespace Controllers\Frontend;


use Bones\Request;
use Bones\Session;
use Google\Service\Adsense\Payment;
use Models\PaymentMethod;
use Models\TransactionLog;
use Omnipay\Omnipay;

class WalletController
{
	public function index(Request $request)
	{
		$paymentMethods = PaymentMethod::where('type', 'both')->orWhere('type', 'wallet')->where('status', 'ACTIVE')->get();
		$transactions = TransactionLog::where('type', 'wallet')->orderBy('id')->get();
		return render('frontend/wallet/index',['paymentMethods' => $paymentMethods, 'transactions' => $transactions]);
		
	}

	public function recharge(Request $request, PaymentMethod $paymentMethod)
	{
		if($paymentMethod->title == 'Wompi' || $paymentMethod->title == 'Mercado Pago') {
			$cop_currency_base_price = currencyConverter('USD', 'COP', 1);
			$balances = [5*$cop_currency_base_price,10*$cop_currency_base_price,15*$cop_currency_base_price,20*$cop_currency_base_price,25*$cop_currency_base_price,50*$cop_currency_base_price];
		} else {
			$balances = [61 => 5.00, 62 => 10.00, 63 => 15.00, 65 => 25.00, 54 =>49.99,60 => 99.99];
		}
		return render('frontend/wallet/recharge',['paymentMethod' => $paymentMethod, 'balances' => $balances]);
	}

	public function rechargePost(Request $request, PaymentMethod $paymentMethod)
	{
		if($paymentMethod->title == 'Mercado Pago') {
			\MercadoPago\SDK::setAccessToken(setting('mercadopago.access_token'));
			$preference = new \MercadoPago\Preference();
			
			$item = new \MercadoPago\Item();
			$item->title = 'Wallet Recharge';
			$item->quantity = 1;
			$item->unit_price = (int) $request->balance;

			$preference->items = array($item);

			$preference->notification_url = 'https://dev.wisencode.com/webhook/mercadopago/notify.php';

			$preference->back_urls = array(
				"success" => route('frontend.payment.mercadopago.success').'?dd_payment_method_id='.$paymentMethod->id.'&amount='.$request->balance,
				"failure" => route('frontend.payment.mercadopago.failure', ['payment_method' => $paymentMethod->id]), 
				"pending" => route('frontend.payment.mercadopago.success').'?dd_payment_method_id='.$paymentMethod->id.'&amount='.$request->balance,

				// "success" => 'https://dev.wisencode.com/webhook/mercadopago/success.php?dd_payment_method_id='.$paymentMethod->id.'&amount='.$request->balance,
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

		if($paymentMethod->title == 'Stripe') {
			\Stripe\Stripe::setApiKey( setting('stripe.secret_key') );
			$session = \Stripe\Checkout\Session::create([
				'line_items' => [[
				  'price_data' => [
					'currency' => $paymentMethod->currency,
					'product_data' => [
					  'name' => 'Digital Deluxes Wallet',
					],
					'unit_amount' => ($request->balance * 100),
				  ],
				  'quantity' => 1,
				]],
				'mode' => 'payment',
				'success_url' => route('frontend.payment.stripe.success', ['payment_method' => $paymentMethod->id]).'?session_id={CHECKOUT_SESSION_ID}',
				'cancel_url' => route('frontend.payment.stripe.failure', ['payment_method' => $paymentMethod->id]),
			  ]);
			  $response = array(
					'status' => 200,
					'stripeSessionId' => $session->id,
					'redirectUrl' => $session->url,
					'payment_method' => $paymentMethod->title
				);
			  return response()->json($response);
		}
		
		if($paymentMethod->title == 'Paypal') {
			$gateway = Omnipay::create('PayPal_Rest');
			$gateway->setClientId(setting('paypal.CLIENT_ID'));
			$gateway->setSecret(setting('paypal.CLIENT_SECRET'));
			$gateway->setTestMode(setting('paypal.sandbox')); //set it to 'false' when go live
			try {
				$response = $gateway->purchase(array(
					'amount' => $request->balance,
					'currency' => setting('paypal.PAYPAL_CURRENCY'),
					'returnUrl' => setting('paypal.PAYPAL_RETURN_URL')."?payment_method=".$paymentMethod->id,
					'cancelUrl' => setting('paypal.PAYPAL_CANCEL_URL')."?payment_method=".$paymentMethod->id,
				))->send();
		 
				if ($response->isRedirect()) {
					$redirectUrl = $response->getData()['links'][1]['href'];
					return response()->json(['status' => 200, 'redirectUrl' => $redirectUrl]);
					// $response->redirect(); // this will automatically forward the customer
				} else {
					// not successful
					echo $response->getMessage();
				}
			} catch(\Exception $e) {
				echo $e->getMessage();
				exit;
			}
		}

		if($paymentMethod->title == 'Coinbase') {
			$curl = curl_init();

			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api.commerce.coinbase.com/charges/',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS =>'{
			"name":"Wallet",
			"description":"Wallet - Digital Deluxes",
			"pricing_type":"fixed_price",
			"local_price": {
				"amount": '.$request->balance.',
					"currency": "'.$paymentMethod->currency.'"
			},
			"metadata":{
				"user_id": '.auth()->id.',
				"price": '.$request->balance.',
				"payment_method_id": "'.$paymentMethod->id.'",
				"payment_type": "wallet"
			},
			"redirect_url": "'.route('frontend.payment.coinbase-wallet.success', ['payment_method' => $paymentMethod->id]).'",
			"cancel_url": "'.route('frontend.payment.coinbase-wallet.cancel', ['payment_method' => $paymentMethod->id]).'"
			}',
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json',
				'X-Cc-Api-Key: '.setting('coinbase.key'),
				'X-Cc-Version: 2018-03-22',
				'Cookie: _session_id=ff3a6e8bb8343e984ffdaf9e18e3e5a3'
			),
			));

			$response = curl_exec($curl);
			
			curl_close($curl);
			$response = json_decode($response);
			return response()->json(['status' => 200, 'redirectUrl' => $response->data->hosted_url]);
		}
	}

	public function rechargeSuccess(Request $request, PaymentMethod $paymentMethod)
	{
		$transactionId = $request->id;
        $api_endpoint = 'https://'.setting('wompi.payment_type').'.wompi.co/v1/transactions/'.$transactionId;
        $response = file_get_contents($api_endpoint);
        $data = json_decode($response);
		$amount = $data->data->amount_in_cents / 100;
		
		$currencyInEur = currencyConverter($paymentMethod->currency, 'EUR', $amount);
		
		$user = user();
		$user->wallet_amount = $user->wallet_amount + $currencyInEur;
		$user->save();

		$transaction = new TransactionLog();
		$transaction->user_id = auth()->id;
		$transaction->tx_id = $data->data->id;
		$transaction->currency = $data->data->currency;
		$transaction->type = 'wallet';
		$transaction->amount = $amount;
		$transaction->status = 'COMPLETED';
		$transaction->payment_method = $paymentMethod->title;
		$transaction->payment_method_id = $paymentMethod->id;
		$transaction->kind_of_tx = 'CREDIT';
		$transaction->save();

		return redirect(route('frontend.wallet.recharge', ['payment_method' => $paymentMethod->id]))->withFlashSuccess('$'.$amount. ' '.$paymentMethod->currency.' added in your wallet successfully')->go();

	}

}