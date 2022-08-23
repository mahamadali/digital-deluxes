<?php

namespace Bones;

use DateTime;
use Bones\DateTimer;
use InvalidArgumentException;

class URL
{
    public static function adjustRoute(string $url)
    {
        $parsedURL = parse_url($url);
        $route = str_replace(setting('app.sub_dir', ''), '', (!empty($parsedURL['path']) ? $parsedURL['path'] : ''));
        if (!empty($parsedURL['query']))
            $route .= '?' . $parsedURL['query'];
        
        return $route;
    }

    public static function removeQuery($url)
    {
        return strtok($url, '?');
    }

    public function addQueryParam($url, $key, $value = '')
    {
        $query = parse_url($url, PHP_URL_QUERY);

        if ($query) {
            parse_str($query, $queryParams);
            $queryParams[$key] = $value;
            $url = str_replace("?$query", '?' . http_build_query($queryParams), $url);
        } else {
            $url .= '?' . urlencode($key) . '=' . urlencode($value);
        }
        
        return (string) url($url);
    }

    public static function addQueryParamToCurrentPage($key, $value = '')
    {    
        return (new static)->addQueryParam(request()->currentPage(), $key, $value);
    }

    public static function getQueryParams($url)
    {
        $parts = parse_url($url);
        return $parts['query'];
    }

    public static function hasQueryParam($url, $param)
    {
        $parts = parse_url($url);
        parse_str($parts['query'], $query);

        return !empty($parts['query']) && isset($query[$param]);
    }

    public static function getQueryParam($url, $param)
    {
        $parts = parse_url($url);
        parse_str($parts['query'], $query);
        
        return (!empty($parts['query']) && isset($query[$param])) ? $query[$param] : '';
    }

}