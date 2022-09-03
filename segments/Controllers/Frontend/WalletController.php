<?php

namespace Controllers\Frontend;


use Bones\Request;
use Bones\Session;
use Google\Service\Adsense\Payment;
use Models\PaymentMethod;
use Models\TransactionLog;

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
			$balances = [5,10,15,20,25,50];
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
			$item->unit_price = $request->balance;

			$preference->items = array($item);

			$preference->notification_url = 'https://dev.wisencode.com/webhook/mercadopago/notify.php';

			$preference->back_urls = array(
				"success" => route('frontend.payment.mercadopago.success').'?dd_payment_method_id='.$paymentMethod->id.'&amount='.$request->balance,
				"failure" => route('frontend.payment.mercadopago.failure', ['payment_method' => $paymentMethod->id]), 
				"pending" => route('frontend.payment.mercadopago.pending', ['payment_method' => $paymentMethod->id])

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
				'cancel_url' => route('frontend.payment.stripe.failure'),
			  ]);
			  $response = array(
					'status' => 200,
					'stripeSessionId' => $session->id,
					'redirectUrl' => $session->url,
					'payment_method' => $paymentMethod->title
				);
			  return response()->json($response);
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