<?php
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