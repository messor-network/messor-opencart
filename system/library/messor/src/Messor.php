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

class Messor
{
    private static $crypt;
    private static $http;
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

    public static function init()
    {
        self::$crypt = new CryptPlain();
        self::$http = new HttpRequest();
        self::$remoteIp = self::$http->server('REMOTE_ADDR');
        self::$settings = Parser::toArraySetting(File::read(Path::SETTINGS));
        self::$sync = Parser::toArrayTab(Parser::toArray(File::read(Path::SYNC_LIST)));
        self::$detectList = Parser::toArraySetting(File::read(Path::DETECT_LIST));
        self::$white = Parser::toArraySetting(File::read(Path::WHITE_LIST));
        self::$rules = unserialize(File::read(Path::RULES));
        self::$isWhite = false;
    }

    /**
     * Ппроверяет пришедший ip адрес на аттаку 
     *
     * @param [string] $ip
     * @return void
     */
    public static function check($ip, $disableDetect, $route, $url = null)
    {
        self::$route = $route;
        self::$url = $url;
        if (!is_null($ip)) {
            self::$remoteIp = $ip;
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
        

        if (file_exists(Path::DB_TREE . Parser::ipFile(self::$remoteIp))) {
            self::$ipBaseList = Parser::toArraySetting(File::read(Path::DB_TREE . Parser::ipFile(self::$remoteIp)));
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
     * Проверяет пришедший запрос на валидный user-agent
     *
     * @param [string] $type
     * @param [string] $string
     * @param [string] $agent
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

    public static function blockDetect($string)
    {
        if (!is_null(self::$detectList)) {
            if (array_key_exists(self::$remoteIp, self::$detectList)) {
                if (self::$detectList[self::$remoteIp] == "forever") {
                    self::block();
                }
                if (self::$detectList[self::$remoteIp] >= self::$settings['block_detect_count']) {
                    self::logger($string, Path::ARCHIVE);
                    if (self::$isWhite == true) return false;
                    self::block();
                    return true;
                }
            }
        }
    }

    /**
     * Ip aдреса из белого листа которые не должны блокироватся
     *
     * @param [string] self::$remoteIp
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
        }
    }

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
     * Проверка Ip адреса на содержание его в базе или в блэк листе,
     * в случае нахождения блокировка
     *
     * @param [array] $ipList
     * @param [string] self::$remoteIp
     * @param [string] $string
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
                    if (self::isWhite()) return false;
                    self::block();
                    return true;
                }
            }
        }
    }

    /**
     * Блокировка по совпадению частей запроса
     *
     * @param [string] $type
     * @param [string] $string
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
                    return true;
                }
            }
        }
    }

    /**
     * Проверка Path
     *
     * @return void
     */
    public static function blockPath($string, $disableDetect)
    {
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
        }
    }

    /**
     * Выбор действия при блокировке
     *
     * @return bool|void
     */
    public static function block()
    {
        switch (self::$settings['lock']) {
            default:
            case "error":
                PageBlocked::codeError('error/not_found');
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

    public static function detectCount()
    {
        if (!is_null(self::$detectList)) {
            if (array_key_exists(self::$remoteIp, self::$detectList)) {
                self::$detectList[self::$remoteIp] += 1;
            } else {
                self::$detectList[self::$remoteIp] = 1;
            }
        } else {
            self::$detectList[self::$remoteIp] = 1;
        }
        try {
            if (!File::clear(Path::DETECT_LIST) && !File::write(Path::DETECT_LIST, Parser::toSettingArray(self::$detectList))) {
                throw new FileException();
            }
        } catch (FileException $e) {
            $e->writeError("Error write to file " . Path::DETECT_LIST . " please check your configuration file.");
        }
        // Выше парога
        if (self::$detectList[self::$remoteIp] >= self::$settings['block_detect_count']) {
            self::block();
        }
    }

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
        try {
            if (!File::write($file, trim($log) . "\n")) {
                throw new FileException();
            }
        } catch (FileException $e) {
            $e->writeError("Error write to file " . $file . " please check your configuration file.");
        }
    }
}
Messor::init();
