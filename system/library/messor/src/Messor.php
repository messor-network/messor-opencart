<?php

namespace src;

use src\Request\HttpRequest;
use src\Logger\Logger;
use src\Exception\FileException;
use src\Request\toServer;
use src\Request\toPeer;
use src\Utils\File;
use src\Config\Path;
use src\Utils\Parser;
use src\Utils\Check;
use src\Crypt\CryptPlain;
use src\PageBlocked;

/**
 * Malicious attack detection
 */
class Messor
{
    private static $crypt;
    private static $http;
    private static $systemSettings;
    private static $settings;
    private static $sync;
    private static $detectList;
    private static $white;
    private static $rules;
    private static $remoteIp;
    private static $url;
    private static $ipBaseList;
    private static $isWhite;
    private static $route;
    private static $pageNotFound;
    private static $signature;

    /**
     * Initialization
     *
     * @return void
     */
    public static function init()
    {
        self::$crypt = new CryptPlain();
        self::$http = new HttpRequest();
        self::$remoteIp = self::$http->server('HTTP_CF_CONNECTING_IP');
        if (!self::$remoteIp) {
            self::$remoteIp = self::$http->server('REMOTE_ADDR');
        }
        self::$settings = Parser::toArraySetting(File::read(Path::SETTINGS));
        self::$systemSettings = Parser::toArraySetting(File::read(Path::SYSTEM_SETTINGS));
        self::$sync = Parser::toArrayTab(Parser::toArray(File::read(Path::SYNC_LIST)));
        self::$detectList = Parser::toArraySettingTab(File::read(Path::DETECT_LIST));
        self::$white = Parser::toArraySetting(File::read(Path::WHITE_LIST));
        self::$rules = unserialize(File::read(Path::RULES));
        self::$signature = Parser::toArraySetting(file::read(Path::SIGNATURE));
        self::$isWhite = false;
    }

    /**
     * Checking the IP address for an attack, the check is carried out according
     * to the included settings in the setting.txt file
     *
     * @param string $ip
     * @param array  $disableDetect
     * @param string   $route
     * @param string|null $url
     * @return void
     */
    public static function check($ip, $disableDetect, $pageNotFound, $route, $url = null)
    {
        self::$pageNotFound = $pageNotFound;
        self::$route = $route;
        self::$url = $url;
        if (is_null($ip)) {
            if (self::$systemSettings['cloudflare'] == 1) {
                self::$remoteIp = self::$http->server('HTTP_CF_CONNECTING_IP');
                if (!self::$remoteIp) {
                    self::$remoteIp = self::$http->server('REMOTE_ADDR');
                }
            } else {
                self::$remoteIp = self::$http->server('REMOTE_ADDR');
            }
        }
        if (self::$settings['block_agent_search_engines'] == 0) {
            if (self::noBlockSearchEngine()) return;
        }

        if (self::$settings['block_ip'] == 1) {
            if (self::noBlockIP()) return;
            if (self::noBlockIPDdos()) return;
        }

        if (self::$settings['block_detect_list']) {
            if (self::blockDetect('detectlist')) return;
        }

        if (self::blockPath('scan', $disableDetect)) return;

        if (self::$settings['block_agent'] == 1) {
            if (self::$settings['block_agent_attack'] == 1) {
                if (self::blockAgent('attack', 'useragent')) return;
            }
            if (self::$settings['block_agent_tools'] == 1) {
                if (self::blockAgent('tools', 'tools')) return;
            }
            if (self::$settings['block_agent_search_engines'] == 1) {
                if (self::blockAgent('search_engines', 'search_engines')) return;
            }
            if (self::$settings['block_agent_bots'] == 1) {
                if (self::blockAgent('bots', 'bot')) return;
            }
            if (self::$settings['block_agent_social'] == 1) {
                if (self::blockAgent('social', 'social')) return;
            }
        }
        if (self::$settings['block_request'] == 1) {
            if (self::$settings['block_request_cookie'] == 1) {
                self::blockRequest(self::$http->cookie(), 'cookie');
            }
            if (self::$settings['block_request_post'] == 1) {
                self::blockRequest(self::$http->post(), 'post');
            }
            if (self::$settings['block_request_get'] == 1) {
                self::blockRequest(self::$http->get(), 'get');
            }
        }

        if (filter_var(self::$remoteIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            if (file_exists(Path::DB_TREE . Parser::ipv4File(self::$remoteIp))) {
                self::$ipBaseList = Parser::toArraySetting(File::read(Path::DB_TREE . Parser::ipv4File(self::$remoteIp)));
            }
        }

        if (filter_var(self::$remoteIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            if (file_exists(Path::DB_TREE . Parser::ipv6File(self::$remoteIp))) {
                self::$ipBaseList = Parser::toArraySetting(File::read(Path::DB_TREE . Parser::ipv6File(self::$remoteIp)));
            }
        }

        if (self::$settings['block_ip'] == 1) {
            self::blockIp(self::$ipBaseList, 'ip');
        }

        if (self::$settings['block_ddos'] == 1) {
            if (self::noBlockIPDdos()) return;
            self::$settings['block_type'] = "page_user";
            self::block();
        }
    }

    /**
     * Checks ip address and user-agent if it belongs to a search engine
     * then skips
     *
     * @return bool
     */
    public static function noBlockSearchEngine()
    {
        $se_list = array(
            'google'       => '\.(google|googlebot)\.com$',
            'yahoo'        => '\.yahoo\.',
            'msn'          => '\.msn\.com$',
            'bing'         => '\.msn\.com$',
            'yandex'       => '\.(yandex|yndx)\.(com|net)$',
            'baidu|bdbrow' => '\.baidu\.(com|jp)$',
            'mail.ru'      => '\.mail\.ru$'
        );
        $agent = strtolower(self::$http->server('HTTP_USER_AGENT'));
        foreach ($se_list as $sengin => $regexp) {
            if (preg_match('#' . $sengin . '#', $agent)) {
                if ($host = gethostbyaddr(self::$remoteIp)) {
                    $host = strtolower($host);
                    if (preg_match('#' . $regexp . '#', $host)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }


    /**
     * Checks the incoming request for a valid user-agent
     *
     * @param string $type
     * @param string $string
     * @param string|null $agent
     * @return bool
     */
    public static function blockAgent($type, $string, $agent = null)
    {
        if (is_null($agent)) {
            $agent = self::$http->server('HTTP_USER_AGENT');
        }

        $list = explode('|', self::$rules['rules']['useragent'][$type]);
        foreach ($list as $item) {
            if (stripos($agent, $item) !== false) {
                self::logger($string, Path::SYNC_LIST);
                if (self::isWhite()) return false;
                self::detectCount();
                return true;
            }
        }
    }

    /**
     * Checking the IP address for finding it in the detect list
     * 
     * @param string $string
     * @return bool
     */
    public static function blockDetect($string)
    {
        if (!is_null(self::$detectList)) {
            foreach (self::$detectList as $item) {
                if (Check::ipIsNet($item['ip'])) {
                    if (Check::ipInSubnet($item['ip'], self::$remoteIp)) {
                        if ($item['day'] > 0) {
                            self::logger($string, Path::ARCHIVE);
                            if (self::$isWhite == true) return false;
                            self::block();
                            return true;
                        }
                    }
                }
                if (self::$remoteIp == $item['ip']) {
                    if ($item['ip'] == "forever") {
                        self::block();
                    }
                    if ($item['day'] > 0) {
                        self::logger($string, Path::ARCHIVE);
                        if (self::$isWhite == true) return false;
                        self::block();
                        return true;
                    }
                }
            }
        }
    }

    /**
     * IP addresses from the whitelist that should not be blocked
     *
     * @return bool
     */
    public static function noBlockIP()
    {
        if (!is_null(self::$white)) {
            if (array_key_exists(self::$remoteIp, self::$white)) {
                self::$isWhite = true;
                if (self::$white[self::$remoteIp] == "forever") {
                    return true;
                }
            }
            foreach (self::$white as $subnet => $day) {
                if (Check::ipIsNet($subnet)) {
                    if (Check::ipInSubnet($subnet, self::$remoteIp)) {
                        self::$isWhite = true;
                        if (self::$white[$subnet] == "forever") {
                            return true;
                        }
                    }
                }
            }
        }
    }

    /** 
     * Unblocked IP addresses with DDOS enabled
     * 
     * @return bool
     */
    public static function noBlockIPDdos()
    {
        if (!is_null(self::$white)) {
            if (array_key_exists(self::$remoteIp, self::$white)) {
                if (self::$white[self::$remoteIp] == "ddos") {
                    return true;
                }
            }
        }
    }

    /**
     * Checking the IP address for its content in the database
     * or in the black list, in case of blocking
     *
     * @param array $ipList
     * @param string $string
     * @return bool
     */
    public static function blockIp($ipList, $string)
    {
        if ($ipList != null) {
            foreach ($ipList as $ip => $value) {
                if (self::$remoteIp == $ip) {
                    if (self::$settings['block_agent'] == 1) {
                        switch ($value) {
                            case 'tools':
                                if (!self::$settings['block_agent_tools']) return false;
                                break;
                            case 'bot':
                                if (!self::$settings['block_agent_bots']) return false;
                                break;
                            case 'search_engines':
                                if (!self::$settings['block_agent_search_engines']) return false;
                                break;
                        }
                    }
                    self::logger($string, Path::ARCHIVE);
                    if (self::$isWhite) return false;
                    self::block();
                    return true;
                }
            }
        }
    }

    /**
     * Blocking on Matching Query Parts
     *
     * @param array $type
     * @param string $string
     * @return bool
     */
    public static function blockRequest($type, $string)
    {
        foreach ($type as $k => $v) {
            if (is_array($v)) {
                self::blockRequest($v, $string);
                continue;
            }
            foreach (self::$rules['rules']['request'] as $rule) {
                preg_match_all('@' . $rule . '@', strtolower($k . '=' . $v), $matches);
                if (preg_match('@' . $rule . '@', strtolower($k . '=' . $v))) {
                    self::logger($string, Path::SYNC_LIST);
                    if (self::isWhite()) return false;
                    self::detectCount();
                    self::block();
                }
            }
        }
        return true;
    }


    /**
     * Checking if the URI matches the request
     *
     * @param string $string
     * @param array $disableDetect
     * @return bool
     */
    public static function blockPath($string, $disableDetect)
    {
        if (self::$signature != null) {
            $tempUrl = parse_url(self::$url, PHP_URL_PATH);
            foreach (self::$signature as $sig => $type) {
                $match = preg_match($sig, $tempUrl);
                if ($match) {
                    if ($type == "except") {
                        return false;
                    } else if ($type == "append") {
                        self::logger($string, Path::SYNC_LIST);
                        if (self::isWhite()) return false;
                        self::detectCount();
                        return true;
                    }
                }
            }
        }
        $server = self::$http->server();
        $result = $server['REQUEST_URI'];
        foreach (self::$rules['rules']['path'] as $url) {
            if (stripos($result, trim($url)) !== false) {
                if (!in_array('path', $disableDetect)) {
                    self::logger($string, Path::SYNC_LIST);
                    if (self::isWhite()) return false;
                    self::detectCount();
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * Checking for IP addresses in the list of allowed addresses
     *
     * @return bool
     */
    public static function isWhite()
    {
        $response = false;
        if (!is_null(self::$white)) {
            if (array_key_exists(self::$remoteIp, self::$white) && (self::$white[self::$remoteIp] != 'ddos')) {
                self::$white[self::$remoteIp] -= 1;
                if (self::$white[self::$remoteIp] < 1) {
                    unset(self::$white[self::$remoteIp]);
                    $response = false;
                } else {
                    $response = true;
                }
                File::clear(Path::WHITE_LIST);
                File::write(Path::WHITE_LIST, Parser::toSettingArray(self::$white));
                return $response;
            }
            foreach (self::$white as $subnet => $day) {
                if (Check::ipIsNet($subnet) && (self::$white[self::$remoteIp] != 'ddos')) {
                    if (Check::ipInSubnet($subnet, self::$remoteIp)) {
                        self::$white[$subnet] -= 1;
                        if (self::$white[$subnet] < 1) {
                            unset(self::$white[$subnet]);
                            $response = false;
                        } else {
                            $response = true;
                        }
                        File::clear(Path::WHITE_LIST);
                        File::write(Path::WHITE_LIST, Parser::toSettingArray(self::$white));
                        return $response;
                        self::block();
                    }
                }
            }
        }
    }

    /**
     * Selecting an action when blocked
     *
     * @return bool|void
     */
    public static function block()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
                return;
            }
        }
        switch (self::$settings['lock']) {
            default:
            case "error":
                PageBlocked::codeError(self::$pageNotFound);
                die;
                break;

            case "redirect":
                PageBlocked::redirect(self::$settings['redirect_url']);
                die;
                break;

            case "block_page":
                PageBlocked::viewPageMessor(self::$settings['block_page_phone'], self::$settings['block_page_email'], self::$settings['message']);
                die;
                break;

            case "js_unlock":
                PageBlocked::viewPageUser(self::$url, self::$route);
                die;
                break;
        }
        return true;
    }

    /**
     * Counting the hit of an address in the detect list,
     * if the number of detections is greater than specified in settings.txt,
     * the IP address is blocked
     *
     * @throws FileException
     * @return void
     */
    public static function detectCount()
    {
        $find = false;
        $day = 0;
        if (!is_null(self::$detectList)) {
            foreach (self::$detectList as $key => $item) {
                if (Check::ipIsNet($item['ip'])) {
                    if (Check::ipInSubnet($item['ip'], self::$remoteIp)) {
                        self::$detectList[$key]['count'] += 1;
                        if (self::$detectList[$key]['count'] >= self::$settings['block_detect_count']) {
                            self::$detectList[$key]['day'] = self::$settings['block_detect_days'];
                        }
                        $find = true;
                        $day = self::$detectList[$key]['day'];
                        break;
                    }
                }
                if (self::$remoteIp == $item['ip']) {
                    self::$detectList[$key]['count'] += 1;
                    if (self::$detectList[$key]['count'] >= self::$settings['block_detect_count']) {
                        self::$detectList[$key]['day'] = self::$settings['block_detect_days'];
                    }
                    $find = true;
                    $day = self::$detectList[$key]['day'];
                    break;
                }
            }
            if (!$find) {
                self::$detectList[] = array('ip' => self::$remoteIp, 'day' => 0, 'count' => 1);
            }
        } else {
            self::$detectList[] = array('ip' => self::$remoteIp, 'day' => 0, 'count' => 1);
        }
        File::clear(Path::DETECT_LIST);
        File::write(Path::DETECT_LIST, Parser::toSettingArrayTab(self::$detectList));
        if ($day > 0) {
            self::block();
        }
    }

    /**
     * Attack logging
     *
     * @param string $type
     * @param string $file
     * @throws FileException
     * @return void
     */
    public static function logger($type, $file)
    {
        $data = '';
        switch ($type) {
            case 'post':
                $data =  Logger::addPost();
                break;
            case 'cookie':
                $data = Logger::addCookie();
                break;
            case 'get':
                $data = Logger::addGet();
                break;
        }
        $log = "\n" . Logger::addIP(self::$remoteIp) . "\t" .
            Logger::addTime() . "\t" .
            Logger::addRequestUri() . "\t" .
            Logger::addUserAgent() . "\t" .
            $type . "\t" .
            $data . "\t";
            File::write($file, trim($log) . "\n");
    }
}
Messor::init();
