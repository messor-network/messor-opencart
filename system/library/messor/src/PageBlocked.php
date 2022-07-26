<?php

namespace src;

use src\Utils\Random;
use src\Request\HttpRequest;
use src\JavaScriptPacker;
use src\Config\User;
use src\Config\Path;
use src\Utils\File;

/**
 * Класс блокировок
 */
class PageBlocked
{
    /**
     * Кодирует скрипт
     *
     * @param string $key
     * @return string
     */
    public static function encodeJsKey($key)
    {
        $part_id = 0;
        $new_key = array();
        for ($i = 0; $i != strlen($key); $i++) {
            $value = $key[$i];
            if (is_numeric($value)) {
                $rand = rand(1, 9);
                $value = "(" . ($rand + $value) . "-" . $rand . ")+";
                @$new_key[$part_id] .= "$value";
            } else if ($value == "'") {
                @$new_key[$part_id] .= "'\\''+";
            } else {
                @$new_key[$part_id] .= "'$value'+";
            }
        }
        return implode("", $new_key) . "''";
    }

    /**
     * Упаковывает Url, Hash, Route в JavaScript ссылку
     * и запускает на странице userblocked
     *
     * @param string $url
     * @param string $route
     * @return void
     */
    public static function viewPageUser($url, $route)
    {
        $http = new HttpRequest();
        $ip = $http->server('HTTP_CF_CONNECTING_IP');
        if (!$ip) {
            $ip = $http->server('REMOTE_ADDR');
        }
        $hash = File::read(PATH::IPHASH . $ip);
        $parts = parse_url($route);
        if (empty($parts['query'])) {
            $script = "
            window.setTimeout(function(){
                window.location.href = '$route?key=$hash&url=$url'}, 1500);
            ";
        } else {
            $script = "
            window.setTimeout(function(){
                window.location.href = '$route&key=$hash&url=$url'}, 1500);
        ";
        };
        $script = self::encodeJsKey($script);
        $packer = new JavaScriptPacker($script, 'Normal', true, false);
        $packed = $packer->pack();
        include "userblocked.html";
    }

    /**
     * Отображает html страницу с информацией телефон, email, сообщение
     *
     * @param string $phone
     * @param string $email
     * @param string $message
     * @return void
     */
    public static function viewPageMessor($phone, $email, $message)
    {
        include "blocked.html";
    }

    /**
     * переадресовывает страницу на указанный url
     *
     * @param string $redirect
     * @return void
     */
    public static function redirect($redirect)
    {
        header('location: ' . $redirect);
    }

    /**
     * переадресовывает на страницу 404
     *
     * @return void
     */
    public static function codeError($pageNotFound)
    {
        header("Location: $pageNotFound");
    }
}
