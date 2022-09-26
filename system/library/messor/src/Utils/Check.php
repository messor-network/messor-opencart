<?php

namespace src\Utils;

/**
 * Class for checking IP addresses and size of Messor files
 */
class Check
{
    /** @var int Maximum file size in MB */
    const FILESIZE = 5;

    /**
     * Search ip in subnets ipv4
     * 
     * @param array $networks
     * @param string $ip
     * @return bool
     */
    static function ip4InSubnet($network, $ip)
    {
        $ip = trim($ip);
        $networks = trim($network);
        if ($ip == $network) {
            return true;
        }
        if (strstr($network, '/')) {
            $ip_arr = explode('/', $network);
            $network_long = ip2long($ip_arr[0]);
            if ($network_long === false) return false;
            $x = ip2long($ip_arr[1]);
            $mask =  long2ip($x) == $ip_arr[1] ? $x : 0xffffffff << (32 - $ip_arr[1]);
            $ip_long = ip2long($ip);
            if (($ip_long & $mask) == ($network_long & $mask)) {
                return true;
            }
        }
        if (strstr($networks, '-')) {
            $ip_arr = explode('-', $network);
            if (ip2long($ip) >= ip2long($ip_arr[0]) && ip2long($ip) <= ip2long($ip_arr[1])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Search ip in subnets ipv6
     * 
     * @param array $networks
     * @param string $ip
     * @return bool
     */

    static function ip6InSubnet($network, $ip)
    {
        try {
            $ip = inet_pton($ip);
        } catch (\Exception $e) {
            return false;
        }
        $ipNet = explode('/', $network);
        $maskParts[0] = (128 - $ipNet[1]) / 8;
        $maskParts[1] = $ipNet[1] / 8;
        $mask = str_repeat(chr(0xff), $maskParts[1]);
        $mask .= str_repeat(chr(0x00), $maskParts[0]);
        $ip = $ip & $mask;
        $ipNet[0] = inet_pton($ipNet[0]);
        if ($ip === $ipNet[0]) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Search ip in subnets ipv4 and ipv6
     * 
     * @param array $networks
     * @param string $ip
     * @return bool
     */
    static function ipInSubnet($networks, $ip)
    {
        return self::ip4InSubnet($networks, $ip) || self::ip6InSubnet($networks, $ip);
    }

    /**
     * Checks the validity of the received ip address ipv4
     * @param string $ip
     * @return bool
     */
    static function ip4Valid($ip)
    {
        $v4pattern = '/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])(\/\d*)?$/';
        $valid = preg_match($v4pattern, $ip);
        return $valid;
    }

    /**
     * Checks if an ip is in a subnet ipv4
     *
     * @param string $ip
     * @return void
     */
    static function ip4IsNet($ip)
    {
        $v4pattern = '/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])(\/\d*)$/';
        $isNet = preg_match($v4pattern, $ip);
        return $isNet;
    }

    /**
     * Checks the validity of the received ip address ipv6
     * @param string $ip
     * @return bool
     */

    static function ip6Valid($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }

    /**
     * Checks if an ip is in a subnet ipv6
     *
     * @param string $ip
     * @return void
     */
    static function ip6IsNet($ip)
    {
        try {
            $isNet = strpos($ip, '/');
            if ($isNet !== false) {
                $ip = substr($ip, 0, $isNet);
                $subnet = inet_pton($ip);
                if ($subnet !== false) {
                    return true;
                }
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Checks the validity of the received ip address ipv4 and ipv6
     * @param string $ip
     * @return bool
     */

    static function ipValid($ip)
    {
        return self::ip4Valid($ip) || self::ip6Valid($ip);
    }

    /**
     * Checks if an ip is in a subnet
     *
     * @param string $ip
     * @return void
     */
    static function ipIsNet($ip)
    {
        return self::ip4IsNet($ip) || self::ip6IsNet($ip);
    }

    /**
     * Checks file size
     *
     * @param string $file
     * @return bool
     */
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
