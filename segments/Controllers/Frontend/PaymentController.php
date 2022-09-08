<?php

namespace Controllers\Frontend;

use Bones\Alert;
use Bones\Request;
use Google\Service\Adsense\Payment;
use Mail\KeysEmail;
use Mail\NewOrderAdminEmail;
use Models\Cart;
use Models\GameKey;
use Models\Order;
use Models\OrderItem;
use Models\PaymentMethod;
use Models\Product;
use Models\ProductKeys;
use Models\TransactionLog;
use Models\User;

class PaymentController
{

	public function index(Request $request)
	{
		dd($request);
	}

    public function check(Request $request)
	{
        // $transactionId = $request->id;
        // $api_endpoint = 'https://'.setting('wompi.payment_type').'.wompi.co/v1/transactions/'.$transactionId;
        // $response = file_get_contents($api_endpoint);
        // $data = json_decode($response);
        
        // if(isset($data->data)) {
        //     $result = $data->data;
        //     $order = new Order();
        //     // $order->reference = $result->reference;
        //     $order->transaction_id = $transactionId;
        //     // $order->payment_method_type = $result->payment_method_type;
        //     // $order->payment_method = json_encode($result->payment_method);
        //     // $order->status = $result->status;
        //     // $order->status_message = $result->status_message;
        //     // $order->currency = $result->currency;
        //     // $order->amount_in_cents = $result->amount_in_cents;
        //     $order->user_id = auth()->id;
        //     $order = $order->save();

        //     $cartItems = cartItems();
        //     foreach($cartItems as $item) {
        //         $orderItem = new OrderItem();
        //         $orderItem->order_id = $order->id;
        //         $orderItem->product_id = $item->product_id;
        //         $orderItem->product_name = $item->product_name;
        //         $orderItem->product_price = $item->product_price;
        //         $orderItem->product_qty = $item->product_qty;
        //         $orderItem->save();
        //     }
        // }

        // $order = new Order();
        // $order->transaction_id = $transactionId;
        // $order->user_id = auth()->id;
        // $order = $order->save();

        // $cartItems = cartItems();
        // foreach($cartItems as $item) {
        //     $orderItem = new OrderItem();
        //     $orderItem->order_id = $order->id;
        //     $orderItem->product_id = $item->product_id;
        //     $orderItem->product_name = $item->product_name;
        //     $orderItem->product_price = $item->product_price;
        //     $orderItem->product_qty = $item->product_qty;
        //     $orderItem->save();
        // }

        // Cart::where('user_id',auth()->id)->delete();
        
        return redirect(route('frontend.payment.success'))->go();

	}

    public function success(Request $request)
	{
        return render('frontend/payment/success');
    }

    public function notify(Request $request)
	{
        //$raw_post_data = '{"event":"transaction.updated","data":{"transaction":{"id":"121271-1662623141-89231","created_at":"2022-09-08T07:45:41.327Z","finalized_at":"2022-09-08T07:45:41.632Z","amount_in_cents":24082487,"reference":"782XDV0WBWGE","customer_email":"akbarmaknojiya@gmail.com","currency":"COP","payment_method_type":"BANCOLOMBIA_TRANSFER","payment_method":{"type":"BANCOLOMBIA_TRANSFER","extra":{"async_payment_url":"https://sandbox.wompi.co/v1/payment_methods/redirect/bancolombia_transfer?transferCode=O9q2lMmgHw9Z2Ip0-approved","external_identifier":"O9q2lMmgHw9Z2Ip0-approved"},"user_type":"PERSON","sandbox_status":"APPROVED","payment_description":"Pago a digitaldeluxes, ref: 782XDV0WBWGE"},"status":"APPROVED","status_message":null,"shipping_address":null,"redirect_url":"https://127.0.0.1/digital-deluxes/payment/check","payment_source_id":null,"payment_link_id":null,"customer_data":{"legal_id":"24234","full_name":"Akbar Husen","phone_number":"+57523423","legal_id_type":"CC"},"billing_data":null}},"sent_at":"2022-09-08T07:45:41.675Z","timestamp":1662623141,"signature":{"checksum":"9b6693e3b40eb58a345d358024b0fad49a5f62944cbe3cddd02dde08aba14394","properties":["transaction.id","transaction.status","transaction.amount_in_cents"]},"environment":"test"}';
        $raw_post_data = file_get_contents('php://input'); 
        file_put_contents('ipn.txt', $raw_post_data);

        $data = json_decode($raw_post_data);
        
        $redirectURLExploded = explode("/", $data->data->transaction->redirect_url);
        
        if(end($redirectURLExploded) == 'recharge-success') {
            exit;
        }
        
        if(isset($data->event)) {
            $user = User::where('email', $data->data->transaction->customer_email)->first();
            $transaction = $data->data->transaction;
            
            $cartItems = cartItems($user->id);
            $cartTotal = cartTotalOriginal($user->id);
		    $total_amount = currencyConverter('EUR', "COP", $cartTotal);
            
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
                $result = $transaction;
                $mannual_order = new Order();
                $mannual_order->reference = $result->reference;
                $mannual_order->transaction_id = $result->id;
                $mannual_order->payment_method_type = $result->payment_method_type;
                $mannual_order->payment_method = json_encode($result->payment_method);
                $mannual_order->status = $result->status;
                $mannual_order->status_message = $result->status_message;
                $mannual_order->currency = $result->currency;
                $mannual_order->amount_in_cents = $manualOrderTotalPrice * 100;
                $mannual_order->order_amount = $manualOrderTotalPrice;
                $mannual_order->user_id = $user->id;
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
            }
            

            

            if(!empty($kinguinOrderItems)) {
                $result = $transaction;
                $order = new Order();
                $order->reference = $result->reference;
                $order->transaction_id = $result->id;
                $order->payment_method_type = $result->payment_method_type;
                $order->payment_method = json_encode($result->payment_method);
                $order->status = $result->status;
                $order->status_message = $result->status_message;
                $order->currency = $result->currency;
                $order->amount_in_cents = $kinguinOrderTotalPrice * 100;
                $order->order_amount = $kinguinOrderTotalPrice;
                $order->user_id = $user->id;
                $order->order_type = 'K';
                $order = $order->save();
                foreach($kinguinOrderItems as $kinguinOrderItem) {
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->product_id = $kinguinOrderItem->product_id;
                    $orderItem->product_name = $kinguinOrderItem->product_name;
                    $orderItem->product_price = $kinguinOrderItem->product_price;
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

                $order_result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);
                file_put_contents('create-order.txt', $order_result);

                $orderData = json_decode($order_result);
                
                $order->kg_orderid = $orderData->orderId ?? '';
                $order->save();
            }

            $paymentMethod = PaymentMethod::where('title', 'Wompi')->first();
            
            $transaction = new TransactionLog();
            $transaction->user_id = $user->id;
            $transaction->tx_id = $result->id;
            $transaction->currency = $result->currency;
            $transaction->type = 'order';
            $transaction->amount = $total_amount;
            $transaction->status = 'COMPLETED';
            $transaction->payment_method = $paymentMethod->title;
            $transaction->payment_method_id = $paymentMethod->id;
            $transaction->kind_of_tx = 'DEBIT';
            $transaction = $transaction->save();
            Cart::where('user_id',$user->id)->delete();   
            // Order placed
            die(204);
        }
    }

    public function fetchKeyType($orderProduct) {
        
        $kinguinId = $orderProduct->product->kinguinId;
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, setting('kinguin.endpoint').'/v1/order');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"products\":[{\"kinguinId\":".$kinguinId.",\"qty\":".$orderProduct->product_qty.",\"name\":\"".$orderProduct->product_name."\",\"price\":".$orderProduct->product_price.",\"keyType\":\"text\"}]}");

        $headers = array();
        $headers[] = 'X-Api-Key: '.setting('kinguin.api_key');
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }

    public function fetchOfferId($keyTypeResponse) {
        
        $kinguinId = $keyTypeResponse->products[0]->kinguinId;
        $offerId = $keyTypeResponse->products[0]->offerId;
        $qty = $keyTypeResponse->products[0]->qty;
        $name = $keyTypeResponse->products[0]->name;
        $price = $keyTypeResponse->products[0]->price;
        
        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, setting('kinguin.endpoint').'/v1/order');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"products\":[{\"kinguinId\":".$kinguinId.",\"qty\":".$qty.",\"name\":\"".$name."\",\"price\":".$price.",\"offerId\":\"".$offerId."\"}]}");

        $headers = array();
        $headers[] = 'X-Api-Key: '.setting('kinguin.api_key');
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }

    public function orderExternalId($keyTypeResponse) {
        
        $kinguinId = $keyTypeResponse->products[0]->kinguinId;
        $qty = $keyTypeResponse->products[0]->qty;
        $name = $keyTypeResponse->products[0]->name;
        $price = $keyTypeResponse->products[0]->price;
        
        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, setting('kinguin.endpoint').'/v1/order');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"products\":[{\"kinguinId\":".$kinguinId.",\"qty\":".$qty.",\"name\":\"".$name."\",\"price\":".$price."}],\"orderExternalId\":\"".strtoupper(random_strings(13))."\"}");

        $headers = array();
        $headers[] = 'X-Api-Key: '.setting('kinguin.api_key');
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return $result;
    }

    public function dispatchOrder($orderId) {

        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, setting('kinguin.endpoint').'/v1/order/dispatch');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"orderId\": \"".$orderId."\"}");

        $headers = array();
        $headers[] = 'X-Api-Key: '.setting('kinguin.api_key');
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }

    public function kg_order_status(Request $request) {
        //$raw_post_data = '{"orderId":"EEI5BK3OQNL","orderExternalId":"PDXC4PJWSTWX","status":"completed","updatedAt":"2022-08-15T11:58:32.275+00:00"}';
        $raw_post_data = file_get_contents('php://input'); 
        file_put_contents('order-status.txt', $raw_post_data);
        $data = json_decode($raw_post_data);
        $status = $data->status;
        $order = Order::where('kg_orderid', $data->orderId)->first();
        $order->kg_order_status = $data->status;
        $order->updated_at = $data->updatedAt;
        $order->save();

        ob_start();

        header("HTTP/1.1 204 NO CONTENT");

        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
        header("Pragma: no-cache"); // HTTP 1.0.
        header("Expires: 0"); // Proxies.

        ob_end_flush(); //now the headers are sent
        exit;
    }

    public function kg_order_complete(Request $request) {
        // $raw_post_data = '{"orderId":"EEI5BK3OQNL","orderExternalId":"PDXC4PJWSTWX","updatedAt":"2022-08-15T11:58:32.261+00:00"}';
        $raw_post_data = file_get_contents('php://input'); 
        file_put_contents('order-complete.txt', $raw_post_data);
        $data = json_decode($raw_post_data);
        $dispatch_data = $this->dispatchOrder($data->orderId);
        $dispatch_data = json_decode($dispatch_data);
        $order = Order::where('kg_orderid', $data->orderId)->first();
        
        $order->kg_order_status = 'completed';
        $order->updated_at = $data->updatedAt;
        $order->dispatchId = $dispatch_data->dispatchId;
        $order = $order->save();

        $game_key_available = GameKey::where('order_id', $order->id)->first();
        if(empty($game_key_available)) {
            $loadKeys = $this->loadKeys($dispatch_data->dispatchId);
            $loadKeys = json_decode($loadKeys);
            
            if(!empty($loadKeys)) {
                foreach($loadKeys as $loadKey) {
                    $product = Product::where('productId', $loadKey->productId)->first();
                    $game_key = new GameKey();
                    $game_key->order_id = $order->id;
                    $game_key->product_id = $product->id;
                    $game_key->serial = $loadKey->serial;
                    $game_key->type = $loadKey->type;
                    $game_key->name = $loadKey->name;
                    $game_key->kinguinId = $loadKey->kinguinId;
                    $game_key->offerId = $loadKey->offerId;
                    $game_key->save();
                }
                $order = Order::find($order->id);
                Alert::as(new KeysEmail($order))->notify();
                Alert::as(new NewOrderAdminEmail($order))->notify();
            }
        }

        ob_start();

        header("HTTP/1.1 204 NO CONTENT");

        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
        header("Pragma: no-cache"); // HTTP 1.0.
        header("Expires: 0"); // Proxies.

        ob_end_flush(); //now the headers are sent
        exit;
        
    }

    public function loadKeys($dispatch_id) {
        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, setting('kinguin.endpoint').'/v1/order/dispatch/keys?dispatchId='.$dispatch_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


        $headers = array();
        $headers[] = 'X-Api-Key: '.setting('kinguin.api_key');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }

    public function mercadopago_success(Request $request)
    {
        // dd($request);
        // if(!$request->has('status')) {
        //     return redirect(route('frontend.wallet.recharge', ['payment_method' => $request->dd_payment_method_id]))->withFlashError('Payment failed! Please try again.')->go();
        // }
        // if($request->status == 'approved') {
            $paymentMethod = PaymentMethod::find($request->dd_payment_method_id);
            $paymentId = $request->payment_id ?? '';
            \MercadoPago\SDK::setAccessToken(setting('mercadopago.access_token'));
            $transaction = \MercadoPago\SDK::get("/v1/payments/".$paymentId);
            
            $currencyInEur = currencyConverter($paymentMethod->currency, 'EUR', $request->amount);
            $user = user();
            $user->wallet_amount = $user->wallet_amount + $currencyInEur;
            $user->save();

            $transaction = new TransactionLog();
            $transaction->user_id = auth()->id;
            $transaction->tx_id = $paymentId;
            $transaction->currency = $paymentMethod->currency;
            $transaction->type = 'wallet';
            $transaction->amount = $request->amount;
            $transaction->status = 'COMPLETED';
            $transaction->payment_method = $paymentMethod->title;
            $transaction->payment_method_id = $paymentMethod->id;
            $transaction->kind_of_tx = 'CREDIT';
            $transaction->save();
            return redirect(route('frontend.wallet.recharge', ['payment_method' => $paymentMethod->id]))->withFlashSuccess('$'.$request->amount. ' '.$paymentMethod->currency.' added in your wallet successfully')->go();
        // } else {
        //     return redirect(route('frontend.wallet.recharge', ['payment_method' => $request->payment_method_id]))->withFlashError('Payment '.$request->status.'!')->go();
        // }
    }

    public function mercadopago_failure(Request $request, PaymentMethod $paymentMethod)
    {
        return redirect(route('frontend.wallet.recharge', ['payment_method' => $paymentMethod->id]))->withFlashSuccess('Payment failed! Please try again.')->go();
    }

    public function mercadopago_pending(Request $request, PaymentMethod $paymentMethod)
    {
        return redirect(route('frontend.wallet.recharge', ['payment_method' => $paymentMethod->id]))->withFlashSuccess('Payment gone into pending. we will add funds in your wallet later.')->go();
    }

    public function stripe_success(Request $request, PaymentMethod $paymentMethod) {
        
        // $stripe = new \Stripe\StripeClient(
        //     setting('stripe.secret_key')
        //   );
        // $session = $stripe->checkout->sessions->retrieve(
        //     $request->session_id,
        //     []
        // );

        $amount = $request->amount;
        
        $currencyInEur = currencyConverter($paymentMethod->currency, 'EUR', $amount);
        $user = user();
        $user->wallet_amount = $user->wallet_amount + $currencyInEur;
        $user->save();
        
        $transaction = new TransactionLog();
        $transaction->user_id = auth()->id;
        $transaction->tx_id = $request->transaction_id;
        $transaction->currency = $paymentMethod->currency;
        $transaction->type = 'wallet';
        $transaction->amount = $amount;
        $transaction->status = 'COMPLETED';
        $transaction->payment_method = $paymentMethod->title;
        $transaction->payment_method_id = $paymentMethod->id;
        $transaction->kind_of_tx = 'CREDIT';
        $transaction->save();
        return redirect(route('frontend.wallet.recharge', ['payment_method' => $paymentMethod->id]))->withFlashSuccess('$'.$amount. ' '.$paymentMethod->currency.' added in your wallet successfully')->go();
    }

    public function stripe_failure(Request $request, PaymentMethod $paymentMethod)
    {
        return redirect(route('frontend.wallet.recharge', ['payment_method' => $paymentMethod->id]))->withFlashError('Payment cancelled! Please try again.')->go();
    }

    public function mercadopago_order_success(Request $request)
    {
        $paymentMethod = PaymentMethod::find($request->dd_payment_method_id);
        $paymentId = $request->payment_id ?? '';
        \MercadoPago\SDK::setAccessToken(setting('mercadopago.access_token'));
        $transaction = \MercadoPago\SDK::get("/v1/payments/".$paymentId);
        $user = user();
        $cartItems = cartItems($user->id);
        
        $cartTotal = cartTotalOriginal($user->id);
		$total_amount = currencyConverter('EUR', "COP", $cartTotal);
        
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
            $mannual_order->transaction_id = $paymentId;
            $mannual_order->payment_method_type = $paymentMethod->title;
            $mannual_order->payment_method = $paymentMethod->title;
            $mannual_order->status = 'APPROVED';
            $mannual_order->status_message = NULL;
            $mannual_order->currency = $paymentMethod->currency;
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
                $mannual_order_item->product_price = currencyConverter('EUR', 'COP', $manualOrderItems->product_price);;
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
            $order->transaction_id = $paymentId;
            $order->payment_method_type = $paymentMethod->title;
            $order->payment_method = $paymentMethod->title;
            $order->status = 'APPROVED';
            $order->status_message = NULL;
            $order->currency = $paymentMethod->currency;
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
            
            $order->kg_orderid = $orderData->orderId;
            $order->save();
        }

            
        $transaction = new TransactionLog();
        $transaction->user_id = $user->id;
        $transaction->tx_id = $paymentId;
        $transaction->currency = $paymentMethod->currency;
        $transaction->type = 'order';
        $transaction->amount = $total_amount;
        $transaction->status = 'COMPLETED';
        $transaction->payment_method = $paymentMethod->title;
        $transaction->payment_method_id = $paymentMethod->id;
        $transaction->kind_of_tx = 'DEBIT';
        $transaction = $transaction->save();

        Cart::where('user_id',$user->id)->delete();

        return redirect(route('frontend.orders.index'))->withFlashSuccess('Order placed!')->go();    
    }

    public function mercadopago_order_failure(Request $request)
    {
        return redirect(route('frontend.checkout.index'))->withFlashSuccess('Payment failed! Please try again.')->go();
    }

    public function mercadopago_order_pending(Request $request)
    {
        return redirect(route('frontend.checkout.index'))->withFlashSuccess('Payment gone into pending.')->go();
    }
    
}