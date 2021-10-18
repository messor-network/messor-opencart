<?php

namespace src\Utils;

class Check
{
    /**
     *  поиск ip в подсетях 
     * @param type $networks - подсеть
     * @param type $ip - ip для сравнения 
     * @return boolean - true есть совпадения false - нет совпадений
     */

    const FILESIZE = 5;

    static function ipNetmatch($networks, $ip)
    {
        $ip = trim($ip);
        for ($i = 0; $i < count($networks); $i++) {
            $networks[$i] = trim($networks[$i]);
            if ($ip == $networks[$i]) {
                return true;
            }
            if (strstr($networks[$i], '/')) {
                $ip_arr = explode('/', $networks[$i]);
                $network_long = ip2long($ip_arr[0]);
                $x = ip2long($ip_arr[1]);
                $mask =  long2ip($x) == $ip_arr[1] ? $x : 0xffffffff << (32 - $ip_arr[1]);
                $ip_long = ip2long($ip);
                if (($ip_long & $mask) == ($network_long & $mask)) {
                    return true;
                }
            }
            if (strstr($networks[$i], '-')) {
                $ip_arr = explode('-', $networks[$i]);
                if (ip2long($ip) >= ip2long($ip_arr[0]) && ip2long($ip) <= ip2long($ip_arr[1])) {
                    return true;
                }
            }
        }
    }

    /**
     * Проверяет полученный ip адрес
     * @param string $ip
     * @return bool
     */
    static function ipValid($ip)
    {
        $v4pattern = '/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])(\/\d*)?$/';
        $valid = preg_match($v4pattern, $ip);
        return $valid;
    }

    static function ipIsNet($ip)
    {
        $v4pattern = '/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])(\/\d*)$/';
        $isNet = preg_match($v4pattern, $ip);
        return $isNet;
    }

    static function fileSize($file)
    {
        $fileOverSize = false;
        $fileSizeBytes = filesize($file);
        $fileSizeMB = ($fileSizeBytes / 1024 / 1024);
        if (self::FILESIZE < $fileSizeMB) {
            $fileOverSize = true;
        } 
        return $fileOverSize;
    }
}
