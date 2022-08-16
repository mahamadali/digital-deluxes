<?php

use Bones\Str;
use Models\User;
use Models\Cart;

if (! function_exists('generateOTP')) {
    /**
     * generate an OTP
     * @param int $length
     * @return string
     */
    function generateOTP($length = 4)
    {
        if(setting('app.stage', 'local') == 'local') {
            return implode('', range(1, $length));
        }
        else {
            return sprintf('%0'.$length.'d', mt_rand(1, str_repeat('9', $length)));
        }
    }
}


if (! function_exists('auth')) {
    function auth()
    {
        return session()->get('auth');
    }
}

if (! function_exists('user')) {
    function user()
    {
        $user = User::find(session()->get('auth')->id);
        return $user;
    }
}

if (! function_exists('old')) {
    function old($element)
    {
        if (!empty(session()->has('old'))) {
            $formData = session()->get('old');
            $old = (!empty($formData) && !empty($formData[$element])) ? $formData[$element] : null;
            $formData[$element] = null;
            session()->set('old', $formData);
            return $old;
        }

        return '';
        
    }
}

if (! function_exists('clearOld')) {
    function clearOld()
    {
        session()->remove('old');
    }
}

if (! function_exists('dd')) {
    function dd($data)
    {
        echo "<pre>";
        print_r($data);
        exit;
    }
}

if (! function_exists('callKinguinApi')) {
    function callKinguinApi($url, $params = [], $type = 'GET')
    {
        $ch = curl_init();

        $url = setting('kinguin.endpoint') . $url;
        if (!empty($params)){
            $url .= '?' . http_build_query($params); 
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        
        $headers = array();
        $headers[] = 'X-Api-Key: '.setting('kinguin.api_key');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception('Curl: ' . curl_error($ch) . ' on ' . $url);
        }

        if (!Str::isJson($response)) {
            return false;
        }

        curl_close($ch);
        
        return json_decode($response);
    }
}

if (! function_exists('isPageWithQueryString')) {
    function isPageWithQueryString()
    {
        $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        if(strpos($url,'?') !== false) {
            return true;
        } else {
            return false;
        }
    }
}

if (! function_exists('currentPageFullURL')) {
    function currentPageFullURL($exclude = [])
    {
        $params = [];
        parse_str($_SERVER['QUERY_STRING'], $params);
        if (!empty($exclude)) {
            foreach ($params as $param => $value) {
                if (in_array($param, $exclude)) {
                    unset($params[$param]);
                }
            }
        }
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].strtok($_SERVER["REQUEST_URI"], '?').'?'.http_build_query($params);
    }
}

if (! function_exists('cartItems')) {
    function cartItems()
    {
        $cart_details  = Cart::where('user_id',auth()->id)->orderBy('id')->get();
        return $cart_details;
    }
}

if (! function_exists('cartTotal')) {
    function cartTotal($userId = '')
    {
        if(empty($userId)) {
            $userId = auth()->id;
        }
        $cart_details  = Cart::where('user_id', $userId)->orderBy('id')->get();
        $total = 0;
        foreach($cart_details as $cart) {
            $total += $cart->product_price * $cart->product_qty;
        }
        return $total;
    }
}

if (! function_exists('random_strings')) {
    function random_strings($length_of_string)
    {
    
        // String of all alphanumeric character
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    
        // Shuffle the $str_result and returns substring
        // of specified length
        return substr(str_shuffle($str_result),
                        0, $length_of_string);
    }
}

if (! function_exists('exchangeRate')) {
    function exchangeRate($price, $from, $to)
    {
        if(cartTotal() > 0) {
            
            // $ch = curl_init();

            // curl_setopt($ch, CURLOPT_URL, 'https://api.apilayer.com/fixer/latest?base='.$from.'&symbols='.$to);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


            // $headers = array();
            // $headers[] = 'Apikey: kb1E3pYgWi3Ua1x2ajWu7ix8YB5lYYuO';
            // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // $result = curl_exec($ch);
            // if (curl_errno($ch)) {
            //     echo 'Error:' . curl_error($ch);
            // }
            // curl_close($ch);
            // $response = json_decode($result);
            
            // $exchange_rate = number_format((float)($price * $response->rates->{$to}), 2, '.', '');
            // $exchange_rate = '17340';
            $exchange_rate = currencyConverter($from, $to, $price);
            return $exchange_rate;
        } else {
            return 0;
        }
        
    }
}

if (! function_exists('getProduct')) {
    function getProduct($kinguinId) {
            
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, setting('kinguin.endpoint').'/v1/products/'.$kinguinId);
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

function currencyConverter($from_Currency,$to_Currency,$amount) {

    $apikey = '12e38b423a9544abc8488f2e';
    $req_url = 'https://v6.exchangerate-api.com/v6/'.$apikey.'/latest/'.$from_Currency;
    $response_json = file_get_contents($req_url);

    // Continuing if we got a result
    if(false !== $response_json) {

        // Try/catch for json_decode operation
        try {

            // Decoding
            $response = json_decode($response_json);
            // Check for success
            if('success' === $response->result) {

                // YOUR APPLICATION CODE HERE, e.g.
                $base_price = $amount; // Your price in USD
                $EUR_price = round(($base_price * $response->conversion_rates->{$to_Currency}), 2);
                return $EUR_price;
            }

        }
        catch(Exception $e) {
            // Handle JSON parse error...
        }

    }
            
}

function getKinguinBalance() {
    // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, setting('kinguin.endpoint').'/v1/balance');
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
    return json_decode($result);

}