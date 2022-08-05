<?php

class URL
{
    public static function adjustRoute(string $url)
    {
        $parsedURL = parse_url($url);
        return str_replace(setting('app.sub_dir', ''), '', (!empty($parsedURL['path']) ? $parsedURL['path'] : ''));
    }
}
