<?php

use Bones\Str;
use Models\User;

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