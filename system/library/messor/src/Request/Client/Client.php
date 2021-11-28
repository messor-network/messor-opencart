<?php

namespace src\Request\Client;


use src\Request\toServer;
use src\Request\toPeer;
use src\Utils\File;
use src\Config\Path;
use src\Exception\FileException;
use src\Exception\ServerException;
use src\Request\Client\Server;
use src\Utils\Parser;

class Client
{
    private $version;
    private $startTime;
    private $peerList;
    private static $log;
    private static $servers;
    private static $peer;
    private static $peerToPeer;
    private static $database;

    public static function init()
    {
        self::$log = '';
        self::$peer = new toServer(null);
        self::$peerToPeer = new toPeer(null);
        self::$servers = new Server(File::read(Path::SERVERS), self::$log);
    }

    /**
     * Запуск клиента
     *
     * @return void
     */
    public static function start()
    {
        $date = date("d.m.y H:i", (int) microtime());
        $start = microtime(true);
        self::$servers->checkServerFile($date);
        $responseList = self::$servers->checkServerStatus(self::$peer);
        self::$servers->displayServerStatus();
        $trustServer = self::$servers->checkTrustServer($responseList);
        self::getPeerInfo($trustServer, self::$peer);
        self::$log .= self::$servers->updateServerList($trustServer, self::$peer);
        self::upgradeClient($trustServer, self::$peer);
        self::synchronization(self::$servers, self::$peer);
        self::$database = new Database($trustServer, self::$servers, self::$peerToPeer, self::$peer, self::$log);
        self::$log .= self::$database->updateDatabase($start);
    }

    public static function update()
    {
        self::start();
        File::write(Path::SYNC_LOG, self::$log);
    }

    public static function changePeerKey($trustServer, toServer $peer)
    {
        $peer->request->setServer($trustServer['server_url']);
        $response = $peer->peerChangeKey();
        $info = Parser::toArraySetting(File::read(Path::INFO));
        $info['url_key'] = $response->getResponseData('url_key');
        File::clear(Path::INFO);
        File::write(Path::INFO, Parser::toSettingArray($info));
    }

    public static function getPeerInfo($trustServer, toServer $peer)
    {
        $peer->request->setServer($trustServer['server_url']);
        $response = $peer->info();
        $info = $response->getResponseData();
        if ($info) {
            File::clear(Path::INFO);
            $info = File::write(Path::INFO, Parser::toSettingArray($info));
        }
    }

    /**
     * Проверка версии клиента
     *
     * @param [array] $trustServer
     * @param toServer $peer
     * @return void
     */
    private static function upgradeClient($trustServer, toServer $peer)
    {
        if (Path::VERSION != $trustServer['client_version']) {
            $newClientVersion = $trustServer['client_version'];
            self::$log .= "You have old version of messor last version is " . $newClientVersion . "\n";
            self::$log .= "Start upgrade.\n";
            $peer->request->setServer($trustServer['server_url']);
            $response = $peer->upgrade();
            if (!empty($response->getResponseData('message'))) {
                self::$log .= $response->getResponseData('message');
                try {
                    File::clear(PATH::UPDATE);
                    if (File::write(PATH::UPDATE, $response->getResponseData())) {
                        self::$log .= " Write info on file " . PATH::UPDATE. "\n";
                    }
                } catch (FileException $e) {
                    self::$log .= "Error write: " . PATH::UPDATE. "\n";
                }
            } 
        } else {
            File::clear(PATH::UPDATE);
            self::$log .= "You have last version of messor: " . Path::VERSION . "\n";
        }
    }

    /**
     * Синхронизируюет файл аттак с сервером
     *
     * @param Response $servers
     * @param toServer $peer
     * @return void
     */
    private static function synchronization($servers, toServer $peer)
    {
        $data = File::read(Path::SYNC_LIST);
        if (strlen($data) > 0) {
            self::$log .= "Read data for sync from file " . Path::SYNC_LIST . " " . strlen($data) . " bytes.\n";
            $syncSuccess = false;
            self::$log .= "Send data on server....\n";
            $serverList = $servers->getServerList();
            foreach ($serverList as $k => $server) {
                $serverUrl = $server[0];
                self::$log .= " Sync:$k {$serverUrl}\t ";
                try {
                    if ($response = $peer->sync()) {
                        if ($response->getStatus() == 'ok') {
                            self::$log .= " [ OK ] - " . trim($response->getResponseData('message')) . "\n";
                            $syncSuccess = true;
                            break (1);
                        } else {
                            self::$log .= " [ {$response->getStatus()} ] - {$response->getResponseData('message')} \n";
                        }
                    }
                } catch (ServerException $e) {
                    // network error
                    self::$log .= " [ error ]\n";
                    self::$log .= "Error: network error or server offline\n";
                    $e->ServerError("Network error or server offline $serverUrl");
                }
            }
            try {
                if ($syncSuccess) {
                    self::$log .= "Synchronization success full.\n";
                    try {
                        if (File::write(Path::ARCHIVE, $data)) {
                            self::$log .= "File archive updated success.\n";
                        }
                    } catch (FileException $e) {
                        self::$log .= "Error write archive file " . Path::ARCHIVE . " Check permission or clean this file manual!\n";
                        $e->writeError("Error write to file " . Path::ARCHIVE);
                        return;
                    }
                    try {
                        if (File::clear(Path::SYNC_LIST) !== false) {
                            self::$log .= "File sync clean success.\n";
                        }
                    } catch (FileException $e) {
                        self::$log .= "Attention - fatal error!\n";
                        self::$log .= "Error clean sync file " . Path::SYNC_LIST . " Check permission or clean this file manual!\n";
                        $e->writeError("Error write to file " . Path::SYNC_LIST);
                        return;
                    }
                }
            } catch (ServerException $e) {
                // All server error/offline
                self::$log .= "Fatal error all servers offline or broken!\n";
                self::$log .= "Check your network connection.\n";
                self::$log .= "You can use emergency server list restore protocol.\n";
                $e->ServerError("Error synchronization - all servers offline or broken error on synchronization");
                return;
            }
        }
    }
}

Client::init();
