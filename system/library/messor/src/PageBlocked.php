<?php

namespace src;

use src\Utils\Random;
use src\Request\HttpRequest;
use src\JavaScriptPacker;
use src\Config\User;
use src\Config\Path;
use src\Utils\File;

class PageBlocked
{
    public static function encodeJsKey($key) {
        $part_id=0;
        $new_key=array();
        for($i=0;$i!=strlen($key); $i++) {
            $value = $key[$i];
            if(is_numeric($value)) {
                $rand=rand(1,9);
                $value = "(" . ($rand+$value) . "-" . $rand . ")+";
                @$new_key[$part_id] .= "$value";
            } else if ($value == "'") {
                @$new_key[$part_id] .= "'\\''+";
            } else {
                @$new_key[$part_id] .= "'$value'+";
            }
        }
        return implode("", $new_key)."''";
    }

    public static function viewPageUser($url, $route)
    {
        $http = new HttpRequest();
        $ip = $http->server('REMOTE_ADDR');
        $hash = File::read(PATH::IPHASH.$ip);
        $script = "
        window.setTimeout(function(){
            window.location.href = '$route&key=$hash&$url'}, 5000);
        ";
        
        $script = self::encodeJsKey($script);
        $packer = new JavaScriptPacker($script, 'Normal', true, false);
        $packed = $packer->pack();
        include "userblocked.html";
    }

    public static function viewPageMessor($phone, $email, $message)
    {
        include "blocked.html";
    }

    public static function redirect($redirect)
    {
        header('location: ' . $redirect);
    }

    public static function codeError()
    {
        header("Location: index.php?route=error/not_found&status=redirect");
    }
}
