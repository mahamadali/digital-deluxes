<?php

use Bones\Str;
use Google\Service\AdExchangeBuyerII\Price;
use Models\User;
use Models\Cart;
use Models\Coupon;
use Models\CurrencyRate;
use Models\PaymentMethod;
use Models\PriceProfit;
use Models\Product;
use Models\Setting;
use Models\SupportTicket;

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
    function cartItems($userId = '')
    {
        if(empty($userId)) {
            if(empty(auth()->id)) {
                return [];
            }
            $userId = auth()->id;
        }
        $cart_details  = Cart::where('user_id',$userId)->orderBy('id')->get();
        return $cart_details;
    }
}

if (! function_exists('cartTotal')) {
    function cartTotal($userId = '')
    {
        if(empty($userId)) {
            if(empty(auth()->id)) {
                return 0;
            }
            $userId = auth()->id;
        }
        $cart_details  = Cart::where('user_id', $userId)->orderBy('id')->get();
        $total = 0;
        foreach($cart_details as $cart) {
            $product_qty = !empty($cart->product_qty) ? $cart->product_qty : 1;
            $total += remove_format($cart->product->price) * $product_qty;
        }
        return $total;
    }
}

if (! function_exists('cartTotalOriginal')) {
    function cartTotalOriginal($userId = '')
    {
        if(empty($userId)) {
            if(empty(auth()->id)) {
                return 0;
            }
            $userId = auth()->id;
        }
        $cart_details  = Cart::where('user_id', $userId)->orderBy('id')->get();
        $total = 0;
        foreach($cart_details as $cart) {
            $total += remove_format($cart->product->price_original) * $cart->product_qty;
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
            if(session()->get('platform_currency') != 'cop') {
                $exchange_rate = currencyConverter($from, $to, $price);
            } else {
                $exchange_rate = $price;
            }
            
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

function currencyConverter($from_Currency,$to_Currency,$amount, $exchange_rate = true) {
    
    // if(session()->has('currency_'.$from_Currency.'_'.$to_Currency) && !empty(session()->get('currency_'.$from_Currency.'_'.$to_Currency))) {
    //     $currency_expire_time = session()->get('currency_expire_time');
    //     $now = strtotime(date('Y-m-d H:i:s'));
    //     $min_diff = round(abs($now - $currency_expire_time) / 60,2);
        
    //     if($min_diff > 60) {
    //         $base_price = callCurrencyApi($from_Currency, $to_Currency , 1, $exchange_rate);
    //         saveCurrencyrate($from_Currency, $to_Currency, $base_price);
    //         session()->set('currency_expire_time', $now);
    //         $value = $base_price * $amount;
    //     } else {
    //         $base_price = getCurrencyrate($from_Currency, $to_Currency);
    //         $value = $amount * (float) $base_price;
    //     }
    // } else {
        
    //     $currency_expire_time = strtotime(date('Y-m-d H:i:s'));
    //     $base_price = callCurrencyApi($from_Currency, $to_Currency, 1, $exchange_rate);

    //     saveCurrencyrate($from_Currency, $to_Currency, $base_price);
        
    //     $value = $amount * (float) $base_price;
    // }

    $base_price = getCurrencyrate($from_Currency, $to_Currency);
    $value = $amount * (float) $base_price;

    return $value;        
}

function saveCurrencyrate($from_Currency, $to_Currency, $base_price) {
    $currencyRate = CurrencyRate::where('from', $from_Currency)->where('to', $to_Currency)->first();
    if(empty($currencyRate)) {
        $currencyRate = new CurrencyRate();
    }
    $currencyRate->from = $from_Currency;
    $currencyRate->to = $to_Currency;
    $currencyRate->rate = $base_price;
    $currencyRate = $currencyRate->save();
    return $currencyRate;
}

function getCurrencyrate($from, $to) {
    $currencyRate = CurrencyRate::where('from', $from)->where('to', $to)->first();
    if(empty($currencyRate)) {
        $base_price = callCurrencyApi($from, $to, 1);
        $currencyRate = saveCurrencyrate($from, $to, $base_price);
    }
    return $currencyRate->rate;
}

function callCurrencyApi($from_Currency,$to_Currency,$amount, $exchange_rate = true) {
    // return $amount;
    
    if($from_Currency == $to_Currency) {
        return $amount;
    }
    $apikey = '833df5053ec167b5379e197e';
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
                if($exchange_rate) {
                    $base_price = $base_price * $response->conversion_rates->{$to_Currency};
                }
                
                return $base_price;
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

if (! function_exists('getProfitPrice')) {
    function getProfitPrice($price)
    {
        if(!empty($priceProfit = PriceProfit::select(function($select) {
            $select->max('min_price')->as('maximum_price');
        })->appendSelect(['profit_perc', 'max_price'])->orderBy('id')->first())) {
            if($priceProfit->maximum_price <= $price) {
                $profit_prices = PriceProfit::where('min_price', (float) $priceProfit->maximum_price)->firstOrNull();
            } else {
                $profit_prices = PriceProfit::where('max_price', '>=', (float) $price)->firstOrNull();
            }
        }

        if (!empty($profit_prices)) {
            $commission = ($profit_prices->profit_perc * $price) / 100;
            $profit_price = $price + $commission;
            return number_format($profit_price, 2);
        } else {
            return 0;
        }
    }
}

if (! function_exists('getProfitCommission')) {
    function getProfitCommission($price, $currency = '')
    {
        
        if(!empty($currency)) {
            $price = currencyConverter($currency, 'EUR', $price);
        }
        
        if(!empty($priceProfit = PriceProfit::select(function($select) {
            $select->max('min_price')->as('maximum_price');
        })->appendSelect('profit_perc')->orderBy('id')->first())) {
            if($priceProfit->maximum_price <= $price) {
                $profit_prices = $priceProfit;
            } else {
                $profit_prices = PriceProfit::where('max_price', '>=', (float) $price)->first();
            }
        }
        
        if (!empty($profit_prices)) {
            $commission = ($profit_prices->profit_perc * $price) / 100;
            return number_format($commission, 2);
        } else {
            return 0;
        }
    }
}

if (! function_exists('admin')) {
    function admin()
    {
        $admin = Setting::where('key', 'receive_email_alerts_at')->first();
        return $admin;
    }
}

if (! function_exists('getUserIP')) {
    function getUserIP() {
        $ipaddress = '';
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        return $ipaddress;
    }
}

if (! function_exists('get_client_ip')) {
    function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}

if (! function_exists('getRegionCountries')) {
    function getRegionCountries($region) {
        $data = file_get_contents('https://api.first.org/data/v1/countries?region='.urlencode($region).'&pretty=true');
        return $data;
    }
}

if (! function_exists('platforms')) {
    function platforms() {
        $products = Product::select(['platform'])->whereNotLike('platform', '%Kinguin%')->whereNotNull('platform')->groupBy('platform')->get()->toArray();
        $products[] = ['platform' => 'PSN Card'];

        
        usort($products, function($a, $b) {
            return strcmp($a["platform"], $b["platform"]);
        });
        
        return $products;
    }
}

if (! function_exists('currencySymbol')) {
    function currencySymbol() {
        $currency = session()->get('platform_currency');
        switch ($currency) {
            case 'eur':
                $symbol = "€";
                break;
            case 'cop':
                $symbol = "$";
                break;
            case 'usd':
                $symbol = "$";
                break;
            
            default:
                $symbol = "$";
                break;
        }
        return $symbol;
    }
}

if (! function_exists('remove_format')) {
    function remove_format($text) {
        $text = str_replace(",", "", $text);
        return $text;
    }
}

if (! function_exists('productLanguages')) {
    function productLanguages() {
        $productLanguages = Product::whereNotNull('languages')->select(['languages'])->groupBy('languages')->get();
        $productLanguagesArray = [];
        foreach($productLanguages as $productLanguage) {
            $product_languages = json_decode($productLanguage->languages);
            foreach($product_languages as $each_p_language) {
                if(!in_array($each_p_language, $productLanguagesArray)) {
                    $productLanguagesArray[] = $each_p_language;
                }
            }
        }
        return $productLanguagesArray;
    }
}

if (! function_exists('productGenres')) {
    function productGenres() {
        $productGenres = Product::whereNotNull('genres')->select(['genres'])->groupBy('genres')->get();
        $productGenresArray = [];
        foreach($productGenres as $productGenre) {
            $product_languages = json_decode($productGenre->genres);
            foreach($product_languages as $each_p_language) {
                if(!in_array($each_p_language, $productGenresArray)) {
                    $productGenresArray[] = $each_p_language;
                }
            }
        }
        return $productGenresArray;
    }
}

if (!function_exists('prevent_csrf')) {
    function prevent_csrf()
    {
        $prevent_csrf_token = md5(uniqid(mt_rand(), true));
        session()->appendSet('prevent_csrf_token', $prevent_csrf_token, true);
        return '<input type="hidden" name="prevent_csrf_token" value="'.$prevent_csrf_token.'" />' . PHP_EOL;
    }
}

if (!function_exists('paymentFee')) {
    function paymentFee($id)
    {
        $payment_method = PaymentMethod::find($id);
        if(!empty($payment_method->transaction_fee)) {
            return $payment_method->transaction_fee;
        } else {
            return 0;
        }
    }
}

if (!function_exists('applyCouponDiscount')) {
    function applyCouponDiscount($cartTotal) {
        if(session()->has('order_coupon')) {
            $coupon_id = session()->get('order_coupon');
            $coupon = Coupon::find($coupon_id);
            $coupon_discount = ($cartTotal * $coupon->percentage) / 100;
            $cartTotal = $cartTotal - $coupon_discount;
            return $cartTotal;
        }
        return $cartTotal;
    }
}

if (!function_exists('orderCouponApply')) {
    function orderCouponApply($order) {
        if(session()->has('order_coupon')) {
            $coupon_id = session()->get('order_coupon');
            $order->coupon_id = $coupon_id;
            $order->save();
            session()->remove('order_coupon');
            return true;
        }
        return true;
    }
}

if (!function_exists('encrypt')) {
    function encrypt($plaintext) {
        return openssl_encrypt($plaintext, 'AES-128-CTR', 'HM', 0, '9182381361541234');
    }
}

if (!function_exists('decrypt')) {
    function decrypt($ciphertext) {
        return openssl_decrypt($ciphertext, 'AES-128-CTR', 'HM', 0, '9182381361541234');
    }
}

if (!function_exists('findForRegion')) {
    function findForRegion($slug) {
        $limitForRegions = ['Turkey' => 'TR', 'Europe' => 'EU', 'United States' => 'US', 'United Kingdom' => 'UK', 'France' => 'FR', 'Germany' => 'DE', 'Lithuania' => 'LT'];
        $slug = strtoupper($slug);
        $explode_slug = explode("-", $slug);
        foreach($limitForRegions as $limitForRegionKey => $limitForRegion) {
            if(in_array(strtoupper($limitForRegion), $explode_slug)) {
                return $limitForRegionKey; 
            }
        }
        return '';
        
    }
}

if (!function_exists('unreadTickers')) {
    function unreadTickers() {
        $unread_tickers =  SupportTicket::where('is_read', 'UNREAD')->count();
        return $unread_tickers;
    }
}
