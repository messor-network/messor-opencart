<?php

namespace src\Logger;

use src\Request\HttpRequest;
use src\Crypt\CryptPlain;

/**
 * Класс логированния
 */
class Logger 
{
    /**
     * Возвращает текущее время
     *
     * @return string
     */
    public static function addTime() 
    {
        return time();
    }
    
    /**
     * Возвращает IP адрес
     *
     * @param string $ip
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
     * Возвращает закодированную Uri строку по которой пришёл запрос
     *
     * @param string|null $requestUri
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
     * Возвращает закодированный User-Agent
     *
     * @param string|null $userAgent
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
     * Возвращает закодированные post данные
     *
     * @param string|null $post
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
    
    /**
     *  Возвращает закодированные cookie данные
     *
     * @param string|null $cookie
     * @return string
     */
    public static function addCookie($cookie = null)
    {
        $http = new HttpRequest();
        if (is_null($cookie)){
            $cookie = $http->cookie();
        }
        $crypt = new CryptPlain();
        return $crypt->Encrypt(http_build_query($cookie));
    }

    /**
     * Возвращает закодированные get данные
     *
     * @param string|null $get
     * @return string
     */
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