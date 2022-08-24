<?php

namespace src;

use src\Config\User;
use src\Crypt\CryptPlain;
use src\Utils\File;
use src\Config\Path;
use src\Utils\Parser;
use src\Utils\Random;
use src\Request\HttpRequest;
use src\Crypt\CryptRC4;
use src\Crypt\CryptOpenSSL;
use src\Logger\Logger;

class Peer
{
    private static $actionListServer = array(
        'ddos'
    );
    private static $actionListPeer = array(
        'peer_ping',
        'peer_get_server_list',
        'peer_get_peer_list',
        'peer_download_database'
    );

    private static $stream;
    private static $httpRequest;
    private static $action;
    private static $header;
    private static $data;
    private static $crypt;
    private static $status;
    private static $content;
    private static $messorLib;
    public static $s = 1;

    public static function init()
    {
        self::$s+=1;
        if (User::$encryptionAlg == "RC4") {
            self::$crypt = new CryptRC4();
        } else {
            self::$crypt = new CryptOpenSSL();
        }
        self::$crypt = new CryptPlain();
        self::$httpRequest = new HttpRequest();
        self::$stream = self::$httpRequest->stream();
    }

    public static function start()
    {
        self::checkAction();
        self::getAction();
        self::chooseCrypt();
        self::parsing();
        self::checker();
        self::$data = Parser::toArraySetting(self::$crypt->decrypt(self::$data));
        self::choiceAction();
        self::$content = self::$crypt->encrypt(Parser::toStringURL(self::$content));
        self::response();
    }

    public static function startForPlugins()
    {
        $start = "Start";
    }

    /**
     * Checking for incoming action
     *
     * @return void
     */
    private static function checkAction()
    {
        if (fread(self::$stream, 7) != 'action=') {
            self::serverError('error_req', "empty headerself:: action");
            die;
        }
    }

    /**
     * Getting action
     *
     * @return void
     */
    private static function getAction()
    {
        while ($read = fread(self::$stream, 1)) {
            self::$action .= $read;
            if ($read == "\n") break;
        }
    }

    private static function chooseCrypt()
    {
        self::$action = trim(self::$action);
        if (in_array(self::$action, self::$actionListServer)) {
            if (USER::$encryptionAlg == "RC4") {
                self::$crypt = new CryptRC4();
            } else {
                self::$crypt = new CryptOpenSSL();
            }
        }
        if (in_array(self::$action, self::$actionListPeer)) {
            self::$crypt = new CryptPlain();
        }
    }

    /**
     * Sending an error to a peer
     *
     * @param [string] $status
     * @param [string] $message
     * @return void
     */
    private static function serverError($status, $message)
    {
        self::$content = array('status' => $status, 'message' => $message);
        self::$status  = trim(self::$status);
        $message = base64_encode(trim($message));
        echo "\n--- BEGIN MESSOR ---\n" .
            "status=" . self::$status . "\n" .
            "server_version=0.4a\n" .
            "*data*" . "\n" .
            "$message\n" .
            "--- END MESSOR ---\n";
    }

    /**
     * Parsing an incoming request from a peer
     *
     * @return void
     */
    private static function parsing()
    {
        $iskey = true;
        $isvalue = false;
        $isdata = false;
        $key = $value = ' ';

        while (!feof(self::$stream)) {
            $read = fgetc(self::$stream);
            if ($read == '=') {
                // delimiter
                $isvalue = true;
                $iskey = false;
                $read = '';
            }
            if ($iskey) {
                // key
                $key .= $read;
            } else if ($isvalue) {
                // value
                $value .= $read;
            } else if ($isdata) {
                // data
                self::$data .= $read;
            }
            if (!$isdata and $read == "\n") {
                self::$header[trim($key)] = trim($value);
                $isvalue = false;
                $iskey = true;
                $key = '';
                $value = '';
            }
            if ($key == "*data*") {
                $isvalue = false;
                $iskey = false;
                $isdata = true;
            }
        }
    }

    /**
     * Checking an incoming request
     *
     * @return void
     */
    private static function checker()
    {
        self::$action = trim(self::$action);
        if (!isset(self::$header) or !is_array(self::$header)) {
            self::serverError('error_req', "parsing request header");
        }

        // Action
        if (empty(self::$action)) {
            self::serverError('error_req', "emptyself:: action header");
        }
        if (!in_array(self::$action, array_merge(self::$actionListPeer, self::$actionListServer))) {
            # TODO detect attack ?
            self::serverError('error_req', "invalid headerself:: action");
        }
        self::$data = trim(self::$data);

        // client version
        if (!isset(self::$header['client_version']) or empty(self::$header['client_version'])) {
            self::serverError('error_req', "empty client_version header");
        }
        $regex = '/^[a-z0-9\.]{3,12}$/';
        if (!preg_match($regex, self::$header['client_version'])) {
            self::serverError('error_req', "invalid client_version");
        }
        
    }

    /**
     * Selecting the action corresponding to the incoming action
     *
     * @return void
     */
    private static function choiceAction()
    {
        switch (self::$action) {
            case "":
            default:
                // Default or empty
                #TODO attack ???? error log ?
                self::serverError('error_req', "Invalid action in your request.");
                break;
                // ddos
            case "ddos":
                if (self::$data['status'] == "enable") {
                    $setting = self::getSetting();
                    $setting["block_ddos"] = 1;
                    self::saveSetting($setting);
                    self::$status = 'ok';
                    self::$content['message'] = 'DDOS enable';
                } else if (self::$data['status'] == "disable") {
                    $setting = self::getSetting();
                    $setting["block_ddos"] = 0;
                    self::saveSetting($setting);
                    self::$status = 'ok';
                    self::$content['message'] = 'DDOS disable';
                }
                break;
            case "peer_ping":
                self::$status = 'ok';
                self::$content['message'] = 'Hi';
                File::write(Path::PEER_LOG, Logger::addTime() ."\t". self::$header['network_id'] ."\t". 'peer_ping' ."\t". 'Peer peer_ping request' ."\t". self::$data ."\n");
                break;
                // peer_get_peer_list
            case "peer_get_peer_list":
                self::$status = 'ok';
                $peerList = File::read(Path::PEERS);
                self::$content = array(
                    'peer_list'       => $peerList,
                );
                File::write(Path::PEER_LOG, Logger::addTime() ."\t". self::$header['network_id'] ."\t". 'peer_get_peer_list' ."\t". 'Peer peer_get_peer_list request' ."\t". self::$data ."\n");
                break;
                // peer_download_database
            case "peer_download_database":
                self::$status = 'ok';
                self::$content = array(
                    'database' => File::read(Path::PATH_DATABASE . File::read(Path::VERSION_BD)),
                );
                File::write(Path::PEER_LOG, Logger::addTime() ."\t". self::$header['network_id'] ."\t". 'peer_download_database' ."\t". 'Peer peer_download_database request' ."\t". self::$data['database_version'] ."\n");
                break;
                // peer_get_server_list
            case "peer_get_server_list":
                self::$status = 'ok';
                self::$content = array(
                    'server_list'     => File::read(Path::SERVERS),
                    'check_sum'       => hash_file('sha256', Path::SERVERS),
                );
                File::write(Path::PEER_LOG, Logger::addTime() ."\t". self::$header['network_id'] ."\t". 'peer_get_server_list' ."\t". 'Peer peer_get_server_list request' ."\t". self::$data ."\n");
                break;
        }
    }

    /**
     * Sending a response
     *
     * @return void
     */
    private static function response()
    {
        echo
            "\n--- BEGIN MESSOR ---\n" .
                "status=" . self::$status . "\n" .
                "server_version=" . Path::VERSION . "\n" .
                "*data*" . "\n"
                . self::$content .
                "\n--- END MESSOR ---\n";
    }

    private static function getSetting()
    {
        return Parser::toArraySetting(File::read(Path::SETTINGS));
    }

    private static function setJsSalt()
    {
        return Random::Rand(7, 12);
    }

    private static function saveSetting($setting)
    {
        unset($setting['module_messor_status']);
        $setting['js_salt'] = self::setJsSalt();
        if ($setting["block_ddos"] == 0) {
            $white = Parser::toArraySetting(File::read(Path::WHITE_LIST));
            if (!empty($white)) {
                foreach ($white as $key => $value) {
                    if ($value == "ddos") {
                        unset($white[$key]);
                    }
                }
                File::clear(Path::WHITE_LIST);
                File::write(Path::WHITE_LIST, Parser::toSettingArray($white));
            }
        }
        if ($setting["block_ddos"] == 1) {
            $setting["lock"] = "js_unlock";
        }
        File::clear(Path::SETTINGS);
        $status = File::write(Path::SETTINGS, Parser::toSettingArray($setting));
        return $status;
    }
}

Peer::init();
Peer::start();
