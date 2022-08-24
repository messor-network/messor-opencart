<?php

namespace src;

use src\Utils\Random;
use src\Request\HttpRequest;
use src\JavaScriptPacker;
use src\Config\User;
use src\Config\Path;
use src\Utils\File;

/**
 * Lock class
 */
class PageBlocked
{
    /**
     * Encodes the script
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
     * Packs Url, Hash, Route into JavaScript link and runs on userblocked page
     *
     * @param string $url
     * @param string $route
     * @return void
     */
    public static function viewPageUser($url, $route)
    {
        $url = urlencode($url);
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
     * Displays html page with information phone, email, message
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
     * Redirects the page to the specified url
     *
     * @param string $redirect
     * @return void
     */
    public static function redirect($redirect)
    {
        header('location: ' . $redirect);
    }

    /**
     * Redirects to a 404 page
     *
     * @return void
     */
    public static function codeError($pageNotFound)
    {
        header("Location: $pageNotFound");
    }
}
