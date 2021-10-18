<?php

namespace src\Logger;

use src\Request\HttpRequest;
use src\Crypt\CryptPlain;

class Logger 
{
    /**
     * Добавляет в лог время
     *
     * @return string
     */
    public static function addTime() 
    {
        return time();
    }
    
    /**
     * Добавляет в лог IP адрес
     *
     * @param [string] $ip
     * @return string
     */
    public static function addIP($ip)
    {
        $http = new HttpRequest();
        if (is_null($ip)){
            $ip = $http->server('REMOTE_ADDR');
        }
        return $ip;
    }

    /**
     * Добавляет в лог строку по которой пришёл запрос
     *
     * @param [string] $requestUri
     * @return string
     */
    public static function addRequestUri($requestUri = null)
    {
        $http = new HttpRequest();
        if (is_null($requestUri)){
            $requestUri = $http->server('REQUEST_URI');
        }
        $crypt = new CryptPlain();
        return $crypt->Encrypt($requestUri);
    }
    
    /**
     * Добавляет в лог User-Agent
     *
     * @param [string] $userAgent
     * @return string
     */
    public static function addUserAgent($userAgent = null)
    {
        $http = new HttpRequest();
        if (is_null($userAgent)){
            $userAgent = $http->server('HTTP_USER_AGENT');
        }
        $crypt = new CryptPlain();
        return $crypt->Encrypt($userAgent);
    }

    /**
     * Добавляет в лог post данные
     *
     * @param [string] $post
     * @return string
     */
    public static function addPost($post = null)
    {
        $http = new HttpRequest();
        if (is_null($post)){
            $post = $http->post();
        }
        $crypt = new CryptPlain();
        return $crypt->Encrypt(http_build_query($post));
    }
    
    public static function addCookie($cookie = null)
    {
        $http = new HttpRequest();
        if (is_null($cookie)){
            $cookie = $http->cookie();
        }
        $crypt = new CryptPlain();
        return $crypt->Encrypt(http_build_query($cookie));
    }

    public static function addGet($get = null)
    {
        $http = new HttpRequest();
        if (is_null($get)){
            $get = $http->get();
        }
        $crypt = new CryptPlain();
        return $crypt->Encrypt(http_build_query($get));
    }
}