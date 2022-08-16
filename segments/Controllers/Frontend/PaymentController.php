<?php

namespace Controllers\Frontend;

use Bones\Alert;
use Bones\Request;
use Mail\KeysEmail;
use Models\Cart;
use Models\GameKey;
use Models\Order;
use Models\OrderItem;
use Models\Product;
use Models\User;

class PaymentController
{

	public function index(Request $request)
	{
		dd($request);
	}

    public function check(Request $request)
	{
        $transactionId = $request->id;
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
        // $raw_post_data = '{"event":"transaction.updated","data":{"transaction":{"id":"121271-1660656381-58355","created_at":"2022-08-16T13:26:21.889Z","finalized_at":"2022-08-16T13:26:22.000Z","amount_in_cents":4048203,"reference":"BWHH04DNRXCK","customer_email":"manknojiya121@gmail.com","currency":"COP","payment_method_type":"BANCOLOMBIA_TRANSFER","payment_method":{"type":"BANCOLOMBIA_TRANSFER","extra":{"async_payment_url":"https://sandbox.wompi.co/v1/payment_methods/redirect/bancolombia_transfer?transferCode=U7yeST3tPZ5PXWDR-approved","external_identifier":"U7yeST3tPZ5PXWDR-approved"},"user_type":"PERSON","sandbox_status":"APPROVED","payment_description":"Pago a digitaldeluxes, ref: BWHH04DNRXCK"},"status":"APPROVED","status_message":null,"shipping_address":null,"redirect_url":"http://dev.wisencode.com/digital-deluxes/payment/check","payment_source_id":null,"payment_link_id":null,"customer_data":{"legal_id":"23242343","full_name":"Mohamad Ali","phone_number":"+57784545454454545","legal_id_type":"CC"},"billing_data":null}},"sent_at":"2022-08-16T13:26:24.351Z","timestamp":1660656384,"signature":{"checksum":"759592ed5a50fa5d760bfd58e7201b0c46f61ce721612bac7d067403d546caac","properties":["transaction.id","transaction.status","transaction.amount_in_cents"]},"environment":"test"}';
        $raw_post_data = file_get_contents('php://input'); 
        file_put_contents('ipn.txt', $raw_post_data);

        $data = json_decode($raw_post_data);
        
        if(isset($data->event)) {
            $user = User::where('email', $data->data->transaction->customer_email)->first();
            $transaction = $data->data->transaction;
            // $order = Order::where('transaction_id', $transaction->id)->first();
            // $order->status = $transaction->status;
            // $order->updated_at = $transaction->finalized_at;
            // $order = $order->save();

            $result = $transaction;
            $order = new Order();
            $order->reference = $result->reference;
            $order->transaction_id = $result->id;
            $order->payment_method_type = $result->payment_method_type;
            $order->payment_method = json_encode($result->payment_method);
            $order->status = $result->status;
            $order->status_message = $result->status_message;
            $order->currency = $result->currency;
            $order->amount_in_cents = $result->amount_in_cents;
            $order->user_id = $user->id;
            $order = $order->save();

            $cartItems = cartItems($user->id);
            foreach($cartItems as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item->product_id;
                $orderItem->product_name = $item->product_name;
                $orderItem->product_price = $item->product_price;
                $orderItem->product_qty = $item->product_qty;
                $orderItem->save();
            }

            Cart::where('user_id',auth()->id)->delete();

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

            $orderData = json_decode($result);

            $order->kg_orderid = $orderData->orderId;
            $order->save();
                
            // Order placed on kinguin
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
    
}