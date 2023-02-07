<?php

namespace src\Request\Client;

use src\Request\toServer;
use src\Utils\File;
use src\Config\Path;
use src\Utils\Parser;

/**
 * Class for working with Messor servers
 */
class Server
{
    private $startTime;
    private $serverList;
    private $fileServer;
    private $statusOk = 0;
    private $statusOff = 0;
    private $statusError = 0;
    private $statusCount = 0;
    private $log;

    /**
     * Initialization
     *
     * @param string $fileServer
     * @param string $log
     */
    public function __construct($fileServer, $log)
    {
        $this->log = $log;
        $this->fileServer = $fileServer;
        $this->serverList = Parser::toArrayTab(Parser::toArray($fileServer));
        $this->startTime = microtime(1);
    }

    /**
     * Checking a file with a list of servers
     *
     * @param string $date
     * @return bool
     */
    public function checkServerFile($date)
    {
        $this->log .= "[*] Start messor sync at " . $date . "\n";
        if (!is_array($this->serverList) or count($this->serverList) == 0) {
            $this->log .= " ! Fatal error no server list! check server list file.\n";
            $this->log .= " Check file server list on file: " . self::$fileServer . "\n";
            $this->log .= " You can find server list at https://messor.network/\n";
            $this->log .= " Exit.\n";
            return false;
        } else {
            return true;
        }
    }

    /**
     * Checking the status of servers
     *
     * @param toServer $peer
     * @return array
     */
    public function checkServerStatus(toServer $peer)
    {
        foreach ($this->serverList as $key => $server) {
            $serverUrl = $server[0];
            $peer->request->setServer($serverUrl);
            $this->log .= " Server:$key {$serverUrl}\t";
            $response = array();
            $start = microtime(true);
            if ($response = $peer->status()) {
                if ($response->getStatus() == 'ok') {
                    $this->log .= " [ ok ]\n";
                    $serverResponseList[$serverUrl] = $response->getResponseData();
                    $resultTime = microtime(true) - $start;
                    $serverResponseList[$serverUrl]['time'] = $resultTime;
                    $this->statusOk++;
                } else {
                    $this->log .= " [{$response->getStatus()}] - {$response->getResponseData()}\n";
                    $this->statusError++;
                }
            } else {
                $this->log .= " [ offline ]\n";
                $this->statusOff++;
            }
            $this->statusCount++;
        }
        return $serverResponseList;
    }

    /**
     * Error output all servers are offline
     *
     * @return void
     */
    private function errorServer()
    {
        $this->log .= "[error]\n";
        $this->log .= "Fatal error all servers offline or broken!\n";
        $this->log .= "Check your network settings or server list\n";
        $this->log .= "You can use emergency server list restore protocol.\n";
        $this->log .= "Fatal error all servers broken or offline\n";
        $this->log .= "Exit.";
    }

    /**
     * Displaying server status
     *
     * @return void
     */
    public function displayServerStatus()
    {
        $this->log .= " " . $this->statusCount . " servers checked at " . round((microtime(1) - $this->startTime), 2) . " second.\n";
        $this->log .= " " . $this->statusOk . " servers response ok \n";
        if ($this->statusError > 0) {
            $this->log .= " $this->statusError server response error \n";
        }
        if ($this->statusOff > 0) {
            $this->log .= " $this->statusOff server offline \n";
        }
        // Error
        if ($this->statusOk == 0) {
            $this->errorServer();
        }
    }

    /**
     * Server trust check
     *
     * @param array $responseList
     * @return array|bool
     */
    public function checkTrustServer($responseList)
    {
        if (!is_array($responseList)) return false;
        foreach ($responseList as $response) {
            $hash[] = $response['server_list_version'] . $response['database_version'];
        }
        $hash = array_count_values($hash);
        $maxMatch = 0;
        foreach ($hash as $k => $value) {
            if ($maxMatch < $value) {
                $key = $k;
                $maxMatch = $value;
            }
        }
        if (isset($hash[$key])) {
            $trustServer['time'] = 25;
            foreach ($responseList as $k => $response) {
                if ($response['server_list_version'] . $response['database_version'] == $key) {
                    if ($response['time'] <= $trustServer['time']) {
                        $trustServer = $response;
                        $trustServer['server_url'] = $k;
                    }
                }
            }
            return $trustServer;
        } else {
            return false;
        }
    }

    /**
     * Server list updates
     *
     * @param array $trustServer
     * @param toServer $peer
     * @return void
     */
    public function updateServerList($trustServer, toServer $peer)
    {
        if (hash_file('sha256', Path::SERVERS) != $trustServer['server_list_version']) {
            $this->log .= "You have old version server list.\n";
            $this->log .= "Your server list check_sum:   " . hash_file('sha256', Path::SERVERS) . "\n";
            $this->log .= "Actual server list check_sum: " . $trustServer['server_list_version'] . "\n";
            $this->log .= "Starting update server list....\n";
            $this->log .= "Get server list from " . $trustServer['server_url'] . "\n";

            $peer->request->setServer($trustServer['server_url']);
            $serverList = $peer->getServerList();
            if (!empty($serverList->getResponseData('server_list'))) {
                $this->log .= "New server list:\n" . $serverList->getResponseData('server_list') . "\n";
                $this->log .= "Write new server list...\n";
                $this->backupServerList($serverList);
            } else {
                $d = $serverList->status;
                if (!empty($serverList->status) and $serverList->status != 'ok') {
                    $dd = $serverList->status;
                    $this->log .= "Response status " . $serverList['status'] . "\t" . $serverList['data'] . "\n";
                } else {
                    $this->log .= "Error get server list from this url.\n";
                }
            }
        } else {
            $this->log .= "You have last version of server list: " . hash_file('sha256', Path::SERVERS) . "\n";
        }
        return $this->log;
    }

    /**
     * Getting a list of servers
     *
     * @return array
     */
    public function getServerList()
    {
        return $this->serverList;
    }

    /**
     * Backup of an old file with a list of servers
     *
     * @param \src\Response\Response $serverList
     * @return void
     */
    private function backupServerList(\src\Response\Response $serverList)
    {
        $serverList = $serverList->getResponseData();
        $backupServerList = File::read(Path::SERVERS);
        File::clear(Path::SERVERS);
        if (File::write(Path::SERVERS, $serverList['server_list'])) {
            $this->log .= "Check sum new server list: " . hash_file('sha256', Path::SERVERS) . "\n";
            if (hash_file('sha256', Path::SERVERS) == $serverList['check_sum']) {
                $this->log .= "Update server list success!\n";
                $this->log .= "Load new server list.\n";
            } else {
                $this->log .= "Fatal error update server list.\n";
                $this->log .= "Attention hash sum mis match!\n";
                $this->log .= "Restore backup server list.\n";
                if ($backupServerList) {
                    File::write(Path::SERVERS . ".backup", $backupServerList);
                    $this->log .= "Backup server list restored.\n";
                    $this->log .= "Tray to find actual server list here https://messor.network/\n";
                } else {
                    $this->log .= "Fatal error restored backup server list.\n";
                    $this->log .= "Update your server list manual.\n";
                    $this->log .= "Tray to find server list at https://messor.network/\n";
                }
                $this->log .= "Exit.";
                return;
            }
        } else {
            $this->log .= "Error update server list. More information in error log.\n";
        }
    }
}
