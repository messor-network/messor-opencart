<?php

namespace main;

// require_once 'Autoloader.php';

use src\Request\Verify;
use src\Request\toServer;
use src\Request\HttpRequest;
use src\Request\Client\Client;
use src\Utils\File;
use src\Utils\Parser;
use src\Utils\Check;
use src\Utils\Random;
use src\Config\Path;
use src\Config\User;
use src\Crypt\CryptPlain;
use src\Messor;
use src\Peer;
use modules\cleaner\MCleaner;
use modules\fscontroll\FSControll;
use modules\fdbbackup\FDBBackup;
use modules\fscheck\FSCheck;
use modules\securitysettings\SecuritySettings;

/**
 * Library for CMS interaction with Messor core
 */
final class MessorLib
{
    public function __construct()
    {
        $this->http = new HttpRequest();
        $this->toServer = new toServer(null);
    }

    /** @return HttpRequest */
    public function HttpRequest()
    {
        return new HttpRequest();
    }

    /** @return toServer */
    public function toServer()
    {
        return new toServer($this->getPrimaryServer());
    }

    /** @return Verify */
    public function verify()
    {
        return new Verify;
    }

    /**
     * Returns a list of servers
     *
     * @return array
     */
    public function getServers()
    {
        return Parser::toArrayTab(Parser::toArray(File::read(Path::SERVERS)));
    }

    /**
     * Returns Error logs
     *
     * @return array|null
     */
    public function getErrorLog()
    {
        return Parser::toArray(File::read(PATH::ERROR));
    }

    /**
     * Returns logs of peer-to-peer interaction
     *
     * @return array|null
     */
    public function getPeerLog()
    {
        return Parser::toArray(File::read(PATH::PEER_LOG));
    }

    /**
     * Returns information about a peer from a file
     *
     * @return array
     */
    public function getAboutPeer()
    {
        return Parser::toArraySetting(File::read(PATH::INFO));
    }

    /**
     * Returns information about a peer from the server and writes to a file
     *
     * @return array
     */
    public function getAboutPeerOfServer()
    {
        $servers = $this->getServers();
        $this->toServer->request->setServer($servers[0][0]);
        $response = $this->toServer->info();
        File::clear(PATH::INFO);
        File::write(PATH::INFO, Parser::toSettingArray($response->getResponseData()));
        return $response->getResponseData();
    }

    public function getAboutPeerOptionsOfServer()
    {
        $servers = $this->getServers();
        $server = new toServer($servers[0][0]);
        $response = $server->peerOptions();
        return $response->getResponseData();
    }

    public function notifyOnServer($type, $level, $result)
    {
        $servers = $this->getServers();
        $this->toServer->request->setServer($servers[0][0]);
        $response = $this->toServer->peerNotify($type, $level, $result);
        return $response->getResponseData();
    }

    /**
     * Returns a list of available peers
     *
     * @return array
     */
    public function getPeerList()
    {
        return Parser::toArray(File::read(PATH::PEERS));
    }

    /**
     * Returns the last sync time
     *
     * @return string
     */
    public function lastSyncTime()
    {
        return date('d.m.Y', File::read(PATH::SYNC_LAST));
    }

    public function isWriteable()
    {
        return is_writable(PATH::SETTINGS);
    }

    public function getVersion()
    {
        return PATH::VERSION;
    }

    /**
     * Clears the transferred file
     *
     * @param string $file
     * @return bool
     */
    public function clearFile($file)
    {
        $file = BASE_PATH . $file;
        $list = $this->getPathList();
        foreach ($list as $key => $value) {
            if (($key == "SERVERS" || $key == "PEERS") && $file == $value) {
                return false;
            }
            if ($file == $value) {
                File::clear($file);
                return true;
            }
        }
        return false;
    }

    /**
     * Returns a file to display
     *
     * @param string $file
     * @return string|bool
     */
    public function viewFile($file)
    {
        $file = BASE_PATH . $file;
        $list = $this->getPathList();
        foreach ($list as $key => $value) {
            if ($key == "SYNC_LOG" && $file == $value) {
                return  File::read($value);
            }
            if ($file == $value) {
                if (File::read($file)) {
                    $log = Parser::toArrayTab(Parser::toArray(File::read($file)));
                    foreach ($log as $key => $value) {
                        $log[$key][0] = date("m.d.Y H:s", (int)$value[0]);
                    }
                } else {
                    return "";
                }
                return Parser::toString(Parser::toStringTab($log));
            }
        }
        return false;
    }

    /**
     * Returns a list of IPs from the Messor database
     *
     * @return array
     */
    public function getDatabaseIPList()
    {
        return Parser::toArray(File::read(Path::DB_IPLIST));
    }

    /**
     * Returns the hash of a file with a list of servers
     *
     * @return string
     */
    public function getHashServerFile()
    {
        return hash_file('sha256', Path::SERVERS);
    }

    /**
     * Returns a list of IP addresses with data that tried to attack the server
     *
     * @param bool $tab
     * @return array|null
     */
    public function getListSynchronization($tab = false)
    {
        if (!$tab) {
            return $this->listSync = Parser::toArray(File::read(Path::SYNC_LIST));
        } else {
            return $this->listSync = Parser::toArrayTab(Parser::toArray(File::read(Path::SYNC_LIST)));
        }
    }

    /**
     * Returns a list of IP addresses with data that tried to attack the server
     * and was transferred to the archive
     * 
     * @param boolean $tab
     * @return array
     */
    public function getListArchive($tab = false)
    {
        if (!$tab) {
            return $this->listArchive = Parser::toArray(File::read(Path::ARCHIVE));
        } else {
            return $this->listArchive = Parser::toArrayTab(Parser::toArray(File::read(Path::ARCHIVE)));
        }
    }

    /**
     * Gets a list of settings
     *
     * @return array
     */
    public function getSetting()
    {
        return Parser::toArraySetting(File::read(Path::SETTINGS));
    }

    /**
     * Gets a list of system settings
     *
     * @return array
     */
    public function getSystemSetting()
    {
        return Parser::toArraySetting(File::read(Path::SYSTEM_SETTINGS));
    }

    /**
     * Gets a list of rules by which an IP is detected as dangerous
     *
     * @return array
     */
    public function getRules()
    {
        $rules = File::read(Path::RULES);
        $rules = unserialize($rules);
        return $rules;
    }

    /**
     * Saves settings
     *
     * @param array $setting
     * @return bool
     */
    public function saveSetting($setting)
    {
        $setting['js_salt'] = $this->setJsSalt();
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

    /**
     * Saves signature
     *
     * @param array $signature
     * @return bool
     */
    public function addSignature($signature)
    {
        $oldSignature = Parser::toArraySetting(File::read(Path::SIGNATURE));
        $oldSignature = $oldSignature != null ? $oldSignature : array();
        $newSignature = array_merge($oldSignature, $signature);
        File::clear(Path::SIGNATURE);
        $status = File::write(Path::SIGNATURE, Parser::toSettingArray($newSignature));
        return $status;
    }

    /**
     * Delete signature
     *
     * @param array $signature
     * @return bool
     */
    public function deleteSignature($signature)
    {
        $oldSignature = Parser::toArraySetting(File::read(Path::SIGNATURE));
        $oldSignature = $oldSignature != null ? $oldSignature : array();
        $newSignature = array_diff_key($oldSignature, $signature);
        File::clear(Path::SIGNATURE);
        if (count($newSignature)) {
            $status = File::write(Path::SIGNATURE, Parser::toSettingArray($newSignature));
        } else {
            $status = true;
        }
        return $status;
    }

    /**
     * Get signature
     *
     * @param array $signature
     * @return bool
     */
    public function getSignature()
    {
        $signature = Parser::toArraySetting(File::read(Path::SIGNATURE));
        return $signature;
    }

    /**
     * Checking for the existence of a user config
     * for interacting with the Messor server
     *
     * @return bool
     */
    public function isConfig()
    {
        return !empty(file_get_contents(Path::USERCONF));
    }

    /**
     * Checking for the existence of the Messor base
     *
     * @return bool
     */
    public function isDatabase()
    {
        if (!File::read(PATH::VERSION_BD)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Checking for a new version of the Messor client
     *
     * @return boolean
     */
    public function isNewVersionMessor()
    {
        if (filesize(Path::UPDATE)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @return string
     */
    public function getVersionDatabase()
    {
        $version = Parser::versionDatabase(File::read(PATH::VERSION_BD));
        if ($version != null) {
            $tmp = str_split($version['version'], 2);
            $tmp[2] = '20' . $tmp[2];
            $tmp = implode('.', $tmp);
            $version['version'] = $tmp;
        }
        return $version;
    }

    /**
     * Returns the messor client version
     *
     * @return void
     */
    public function versionPlugin()
    {
        return Path::VERSION;
    }

    /**
     * Gets the text to output to the Messor client about the new version
     * of the client
     *
     * @return string
     */
    public function txtNewVersionMessor()
    {
        $file = File::read(Path::UPDATE);
        $file = htmlspecialchars($file);
        $file = str_replace("\n", "</br>", $file);
        return $file;
    }

    // public function isCloudFlare()
    // {
    //     $ip = $this->http->server('HTTP_CF_CONNECTING_IP');
    //     if ($ip) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    /**
     * Checks if CloudFlare is enabled on the server.
     * If successful, enables CloudFlare compatible IP address
     * processing for Messor
     *
     * @return bool
     */
    public function onCloudFlare()
    {
        $systemSetting = Parser::toArraySetting(File::read(Path::SYSTEM_SETTINGS));
        $ip = $this->http->server('HTTP_CF_CONNECTING_IP');
        if ($ip) {
            $systemSetting['cloudflare'] = 1;
            File::clear(Path::SYSTEM_SETTINGS);
            File::write(Path::SYSTEM_SETTINGS, Parser::toSettingArray($systemSetting));
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if CloudFlare is enabled on the server.
     * In case of a negative result and CloudFlare c Messor compatibility
     * is enabled, returns true otherwise false
     *
     * @return bool
     */
    public function offCloudFlare()
    {
        $ip = $this->http->server('HTTP_CF_CONNECTING_IP');
        if (!$ip) {
            $systemSetting = Parser::toArraySetting(File::read(Path::SYSTEM_SETTINGS));
            if ($systemSetting['cloudflare'] == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Disables CloudFlare compatibility with Messor in system settings
     *
     * @return bool
     */
    public function offCloudFlareAjax()
    {
        $systemSetting = Parser::toArraySetting(File::read(Path::SYSTEM_SETTINGS));
        $systemSetting['cloudflare'] = 0;
        File::clear(Path::SYSTEM_SETTINGS);
        $res = File::write(Path::SYSTEM_SETTINGS, Parser::toSettingArray($systemSetting));
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns a list of files for which it is possible to get logs
     *
     * @return array
     */
    public function getLogs()
    {
        $logs['error']['log'] = File::read(Path::ERROR);
        $logs['peer']['log'] = File::read(Path::PEER_LOG);
        $logs['sync']['log'] = File::read(Path::SYNC_LOG);
        $logs['error']['path'] = Path::ERROR;
        $logs['peer']['path'] = Path::PEER_LOG;
        $logs['sync']['path'] = Path::SYNC_LOG;

        return $logs;
    }

    /**
     * List of database files in different formats
     *
     * @return array
     */
    public function getPathDBIP()
    {
        $path = array();
        $path['iplist'] = Path::DB_IPLIST;
        $path['iptables'] = Path::DB_IPTABLES;
        $path['path'] = Path::DB_HTACCESS;
        return $path;
    }

    /**
     * List of available files for viewing
     *
     * @return array
     */
    public function getPathList()
    {
        $list = array();
        $list['DETECT_LIST'] = Path::DETECT_LIST;
        $list['WHITE_LIST']  = Path::WHITE_LIST;
        $list['SYNC_LIST']   = Path::SYNC_LIST;
        $list['ARCHIVE']     = Path::ARCHIVE;
        $list['SERVERS']     = Path::SERVERS;
        $list['PEERS']       = Path::PEERS;
        $list['PEER_LOG']    = Path::PEER_LOG;
        $list['ERROR']       = Path::ERROR;
        $list['SYNC_LOG']    = Path::SYNC_LOG;
        return $list;
    }

    /**
     * Adds an id field to the list so that an element can be removed by its id
     *
     * @param array $list
     * @return array
     */
    public function addIDInList($list)
    {
        $listAddId = array();
        $i = 0;
        foreach ($list as $item) {
            $listAddId[$i]['id'] = $i;
            $listAddId[$i][] = $item;
            $i++;
        }
        return $listAddId;
    }

    /**
     * Removes an element from the list by its id
     *
     * @param array $list
     * @return array
     */
    public function deleteIDInList($list)
    {
        foreach ($list as &$item) {
            unset($item['id']);
            if (isset($item[0])) {
                $newList[] = $item[0];
            } else if (isset($item)) {
                $newList[] = $item;
            }
        }
        return $newList;
    }

    /**
     * Adds an id field to the list so that an element can be removed by its id
     *
     * @param array $list
     * @return array
     */
    public function addIDInListModal($list)
    {
        $i = 0;
        foreach ($list as &$item) {
            $item['id'] = $i;
            $i++;
        }
        return $list;
    }

    /**
     * Removes an element from the list by its id
     *
     * @param array $list
     * @return array
     */
    public function deleteIDInListModal($list)
    {
        foreach ($list as &$item) {
            unset($item['id']);
        }
        return $list;
    }

    /**
     * Decodes array elements by keys in $keys variable
     *
     * @param array $list
     * @param array $keys
     * @return array
     */
    public function decryptArray(&$list, $keys)
    {
        $plain = new CryptPlain();
        foreach ($keys as $key) {
            foreach ($list as &$item) {
                if (isset($item[$key])) {
                    $item[$key] = $plain->decrypt($item[$key]);
                }
            }
        }
        return $list;
    }

    /**
     * Gets IP address if CloudFlare is enabled
     * tries to get HTTP_CF_CONNECTING_IP then checks received IP
     * if empty gets REMOTE_ADDR
     *
     * @param bool $cf
     * @return string
     */
    public function getIP($cf = false)
    {
        $ip = $cf ? $this->http->server('HTTP_CF_CONNECTING_IP') :
            $this->http->server('REMOTE_ADDR');
        $ip = $ip ?: $this->http->server('REMOTE_ADDR');
        return $ip;
    }

    /**
     * Sets the server for requests
     *
     * @return string
     */
    public function getPrimaryServer()
    {
        $servers = $this->getServers();
        $primary = array();
        foreach ($servers as $server) {
            if ($server[0]) {
                $primary = $server[0];
                break;
            }
        }
        return $primary;
    }

    /**
     * Checking the key transmitted by the peer if the key is valid then
     * the peer can accept the request
     *
     * @param string $key
     * @return void
     */
    public function requestToPeer($key)
    {
        $info = Parser::toArraySetting(File::read(Path::INFO));
        if ($info['url_key'] == $key) {
            Peer::startForPlugins();
        }
    }

    /**
     * Checks the key with the hash from the file, if the keys match,
     * then unlocking takes place
     *
     * @param string $key
     * @return bool
     */
    public function hashJs($key)
    {
        $setting = $this->getSetting();
        $systemSetting = Parser::toArraySetting(File::read(Path::SYSTEM_SETTINGS));
        $http = new HttpRequest();
        if ($systemSetting['cloudflare'] == 1) {
            $ip = $http->server('HTTP_CF_CONNECTING_IP');
            if (!$ip) {
                $ip = $http->server('REMOTE_ADDR');
            }
        } else {
            $ip = $http->server('REMOTE_ADDR');
        }
        $hashKey = File::read(PATH::IPHASH . $ip);
        if ($hashKey == $key) {
            if ($setting["block_ddos"] == 1) {
                File::write(Path::WHITE_LIST, $ip . " = " . "ddos" . "\n");
            } elseif ($setting["lock"] == "js_unlock") {
                File::write(Path::WHITE_LIST, $ip . " = " . "1" . "\n");
            }
            $detect_list = Parser::toArraySettingTab(File::read(Path::DETECT_LIST));
            if (is_array($detect_list)) {
                foreach ($detect_list as $key => $item) {
                    if ($item['ip'] == $ip) {
                        //File::write(Path::WHITE_LIST, $ip . " = " . "3" . "\n");
                        unset($detect_list[$key]);
                        break;
                    }
                }

                File::clear((Path::DETECT_LIST));
                if ($detect_list != null) {
                    File::write(Path::DETECT_LIST, Parser::toSettingArrayTab($detect_list));
                }
            }
        }
        return true;
    }

    /**
     * Checks for the existence of a hash file for the given IP address
     * @param string $ip
     * @return bool
     */
    public function existHashIP($ip)
    {
        return file_exists(PATH::IPHASH . $ip);
    }

    /**
     * Adds a hash for an IP address to a file
     *
     * @param string $ip
     * @param string $js_salt
     * @return void
     */
    public function addHashIP($ip, $js_salt)
    {
        $string = '';
        $string .= $ip . $js_salt;
        $hash = hash('sha256', $string);
        File::write(PATH::IPHASH . $ip, $hash);
    }

    /**
     * Deletes all files with hashes for IP
     *
     * @return void
     */
    public function deleteAllHashIP()
    {
        $files = scandir(PATH::IPHASH);
        foreach ($files as $file) {
            if (!is_dir($file)) {
                unlink(PATH::IPHASH . $file);
            }
        }
    }

    /**
     * Checking servers online|offline
     *
     * @param array $list
     * @param string $key
     * @return array
     */
    public function checkServersOnline($list, $key)
    {
        foreach ($list as &$item) {
            if (is_array($item)) {
                $this->toServer->request->setServer($item[0]);
            } else {
                $this->toServer->request->setServer($item);
            }
            $response = $this->toServer->ping();
            $status = $response->getStatus();
            if (is_array($item)) {
                if ($status == "ok") {
                    $item[$key] = "online";
                } else {
                    $item[$key] = "offline";
                }
            } else {
                if ($status == "ok") {
                    $item = "online";
                } else {
                    $item = "offline";
                }
            }
        }
        return $list;
    }

    /**
     * Installs Salt
     *
     * @return string
     */
    public function setJsSalt()
    {
        return Random::Rand(7, 12);
    }

    /**
     * @see Client::update()
     * @return array
     */
    public function updateClient()
    {
        return Client::update();
    }

    /**
     * Checks the size of files with logs; if the size is exceeded,
     * returns the file name
     *
     * @return array
     */
    public function checkFileSize()
    {
        $check['error'] = Path::ERROR;
        $check['peer'] = Path::PEER_LOG;
        $check['archive'] = Path::SYNC_LOG;

        $answer = array();
        foreach ($check as $key => $value) {
            if (Check::fileSize($value)) {
                $answer[$key] = $value;
            }
        }
        return $answer;
    }

    /** @see Messor::check() */
    public function check($ip, $disableDetect, $pageNotFound, $route, $url = null)
    {
        Messor::check($ip, $disableDetect, $pageNotFound, $route, $url);
    }

    /**
     * Checks if the IP address is valid or if the IP address is a valid subnet
     *
     * @param string $item
     * @return bool
     */
    public function checkIP($item)
    {
        if (Check::ipValid($item) || Check::ipIsNet($item)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Gets the Messor network user config
     * @return array
     */
    public function getConfigUser()
    {
        $version = Path::VERSION;
        $conf = Parser::toArraySetting(File::read(Path::USERCONF));
        $conf['version'] = $version;
        return $conf;
    }

    /**
     * Passes an array with attacks to be sorted according
     * to the passed $name parameter
     *
     * @param string $name
     * @param array $list
     * @param string $sortDirection
     * @return array
     */
    public function sortAttack($name, $list, $sortDirection)
    {
        $this->timeUnixToDate($list, 1);
        switch ($name) {
            case 'ip_attack':
                $this->sortingArray($list, $sortDirection, 0);
                return $list;
                break;
            case 'time_attack':
                $this->sortingArray($list, $sortDirection, 1);
                return $list;
                break;
            case 'url_attack':
                $this->sortingArray($list, $sortDirection, 2);
                return $list;
                break;
            case 'user_agent':
                $this->sortingArray($list, $sortDirection, 3);
                return $list;
                break;
            case 'type_attack':
                $this->sortingArray($list, $sortDirection, 4);
                return $list;
                break;
            case 'post':
                $this->sortingArray($list, $sortDirection, 5);
                return $list;
                break;
        }
    }

    /**
     * Convert Unix time time to date
     *
     * @param array $list
     * @param int $key
     * @return void
     */
    private function timeUnixToDate(&$list, $key)
    {
        foreach ($list as &$item) {
            if ($item[0] == '') {
                continue;
            } else {
                $item[$key] = date('Y.m.d H:i', $item[$key]);
            }
        }
    }

    /**
     * Sorts an array
     *
     * @param array $list
     * @param string $sortDirection
     * @param int|null $key
     * @return void
     */
    public function sortingArray(&$list, $sortDirection, $key = null)
    {
        if ($list) {
            foreach ($list as &$item) {
                $tmp[] = &$item[$key];
            }
            if ($sortDirection == "ASC") {
                array_multisort($tmp, SORT_ASC, SORT_NATURAL, $list);
                return $list;
            } else {
                array_multisort($tmp, SORT_DESC, SORT_NATURAL, $list);
                return $list;
            }
        }
    }

    /**
     * Returns data for pagination
     *
     * @param int $total
     * @param int $limit
     * @param int $page
     * @param array $list
     * @return array
     */
    public function pagination($total, $limit, $page, $list)
    {
        $numPage = ceil($total / $limit);
        $list = array_slice($list, $limit * ($page - 1), $limit);
        $listUrl = array();
        if ($numPage < 6) {
            for ($i = 1; $i <= $numPage; $i++) {
                if ($i == $page) {
                    $listUrl[$i] = "disabled";
                    continue;
                }
                $listUrl[$i] = $i;
            }
        } else {
            $begin = $page <= 2 ? 1 : $page - 2;
            $last = $page < $numPage - 2 ? $page + 2 : $numPage;
            for ($i = $begin, $j = $last; $i < $page, $page <= $j; $i++, $j--) {
                $listUrl[$i] = $i;
                $listUrl[$j] = $j;
                if ($i + 1 == $j - 1) {
                    $listUrl[$j - 1] = $j - 1;
                }
            }
            $listUrl[$page] = "disabled";
            ksort($listUrl);
        }
        if ($numPage > 1) {
            $prev = $page != 1 ? $listUrl[$page - 1] : "disabled";
            $next = $page != $numPage ? $listUrl[$page + 1] : "disabled";
        } else {
            $prev = 0;
            $next = 0;
        }
        return array($list, $listUrl, $numPage, $prev, $next);
    }

    /**
     * Adds an IP address to the allow or detect list
     *
     * @param string $type
     * @param array $newlist
     * @return bool
     */
    public function addIP($type, $newlist)
    {
        $oldlist = $this->getListIP($type);
        if ($type == 'black') {
            array_walk($newlist, function ($item, $key) use ($oldlist, &$newlist) {
                foreach ($oldlist as $itemOldList) {
                    if ($item['ip'] == $itemOldList['ip']) {
                        unset($newlist[$key]);
                    }
                }
            });
        }
        $list = array_merge($oldlist, $newlist);
        return $this->setListIP($type, $list);
    }

    /**
     * Removes the IP address of the allow or detect list
     *
     * @param string $type
     * @param array $list
     * @param string $ip
     * @return bool
     */
    public function deleteIP($type, $list, $ip)
    {
        foreach ($list as $key => &$value) {
            if ($type == 'black') {
                if ($value['ip'] == $ip) {
                    unset($list[$key]);
                }
            } else {
                if ($key == $ip) {
                    unset($list[$ip]);
                }
            }
        }
        return $this->setListIP($type, $list);
    }

    /**
     * Search IP address allow or detect list
     *
     * @param string $type
     * @param array $list
     * @param string $ip
     * @return bool
     */
    public function searchIP($list, $ip, $type)
    {
        foreach ($list as $key => $value) {
            if ($type == 'black') {
                if ($value['ip'] == $ip) {
                    $list = array($value['ip'] => $value['day']);
                    return $list;
                }
            } else {
                if ($key == $ip) {
                    $list = array($ip => $value);
                    return $list;
                }
            }
        }
        $list = array('null' => null);
        return $list;
    }

    /**
     * Returns a list of IP addresses from the allow or detect list
     *
     * @param string $type
     * @return array
     */
    public function getListIP($type)
    {
        if ($type == "white") {
            $result = Parser::toArraySetting(File::read(Path::WHITE_LIST));
            if ($result) return $result;
            else return array();
        } else {
            $result = Parser::toArraySettingTab(File::read(Path::DETECT_LIST));
            if ($result)
                return $result;
            else return array();
        }
    }

    /**
     * Overwrites a file with IP addresses
     * 
     * @param string $type
     * @param array $list
     * @return bool
     */
    public function setListIP($type, $list)
    {
        if ($type == "white") {
            File::clear(PATH::WHITE_LIST);
            return (File::write(Path::WHITE_LIST, Parser::toSettingArray($list)));
        } else {
            File::clear(PATH::DETECT_LIST);
            return (File::write(Path::DETECT_LIST, Parser::toSettingArrayTab($list)));
        }
    }

    /**
     * Overwrites file with attacks
     *
     * @param array $list
     * @return bool
     */
    public function setListSync($list)
    {
        File::clear(PATH::SYNC_LIST);
        return (File::write(Path::SYNC_LIST, Parser::toString($list)));
    }

    /**
     * Overwrites file with attacks
     *
     * @param array $list
     * @return bool
     */
    public function setListSyncModal($list)
    {
        File::clear(PATH::SYNC_LIST);
        return (File::write(Path::SYNC_LIST, Parser::toString(Parser::toStringTab($list))));
    }

    /**
     * Overwrites the file with attacks in the archive
     *
     * @param array $list
     * @return bool
     */
    public function setListArchive($list)
    {
        File::clear(PATH::ARCHIVE);
        return (File::write(Path::ARCHIVE, Parser::toString(Parser::toStringTab($list))));
    }

    /**
     * Removes an attack from the list of attacks
     *
     * @param int $id
     * @return bool
     */
    public function deleteSyncItem($id)
    {
        $list = $this->getlistSynchronization();
        $list = $this->addIDInList($list);
        foreach ($list as $i => $item) {
            if ($item['id'] == $id) {
                unset($list[$i]);
                break;
            }
        }
        $list = $this->deleteIDInList($list);
        return $this->setListSync($list);
    }

    /**
     * Removes an attack from the list with attacks from the archive
     *
     * @param int $id
     * @return bool
     */
    public function deleteArchiveItemModal($id)
    {
        $list = $this->getlistArchive(true);
        $list = $this->addIDInListModal($list);
        foreach ($list as $i => $item) {
            if ($item['id'] == $id) {
                unset($list[$i]);
                break;
            }
        }
        $list = $this->deleteIDInListModal($list);
        return $this->setListArchive($list);
    }

    /**
     * Removes an attack from the list of attacks
     *
     * @param int $id
     * @return bool
     */
    public function deleteSyncItemModal($id)
    {
        $list = $this->getlistSynchronization(true);
        $list = $this->addIDInListModal($list);
        foreach ($list as $i => $item) {
            if ($item['id'] == $id) {
                unset($list[$i]);
                break;
            }
        }
        $list = $this->deleteIDInListModal($list);
        return $this->setListSyncModal($list);
    }

    /**
     * Generates a login for Messor
     *
     * @return string
     */
    public function generateLogin()
    {
        return Random::Rand(4, 7, "lu");
    }

    /**
     * Generates a password for Messor
     *
     * @return string
     */
    public function generatePassword()
    {
        return Random::Rand();
    }

    /**
     * Returns a list of countries to register
     *
     * @return array
     */
    public function getCountryList()
    {
        return Parser::toArraySetting(File::read(BASE_PATH . "/data/install/country_list.txt"));
    }

    /**
     * Checks file creation time
     *
     * @param int $hour
     * @param string $file
     * @return void
     */
    public function checkUpdateDay($hour, $file)
    {
        if (time() - filemtime($file) <= ($hour * 60 * 60)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Removes 1 unit from the detection file for the IP address,
     * when IP is equal to 0,
     * the address will be removed from the detection list
     *
     * @return void
     */
    public function deleteScoresDetect()
    {
        if (filesize(PATH::DETECT_LIST)) {
            $detectList = Parser::toArraySettingTab(File::read(Path::DETECT_LIST));
            foreach ($detectList as $key => $value) {
                if ($value['day'] != "forever") {
                    $detectList[$key]['day'] -= 1;
                    if ($detectList[$key]['day'] <= 0) {
                        unset($detectList[$key]);
                    }
                }
            }
            File::clear(Path::DETECT_LIST);
            File::write(Path::DETECT_LIST, Parser::toSettingArrayTab($detectList));
        }
    }

    public function deleteScoresAllow()
    {
        if (filesize(PATH::WHITE_LIST)) {
            $allow = Parser::toArraySetting(File::read(Path::WHITE_LIST));
            foreach ($allow as $cur_ip => $day) {
                if ($day != "forever" && $day != "ddos") {
                    $allow[$cur_ip] -= 1;
                    if ($allow[$cur_ip] <= 0) {
                        unset($allow[$cur_ip]);
                    }
                }
            }
            File::clear(Path::WHITE_LIST);
            File::write(Path::WHITE_LIST, Parser::toSettingArray($allow));
        }
    }

    /**
     * Validates and returns text on Messor update
     *
     * @return string
     */
    public function newVersion()
    {
        if (filesize(Path::UPDATE)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * User registration in the Messor network
     *
     * @param array $data
     * @return array
     */
    public function register($data)
    {
        foreach ($data as $k => $v) {
            $this->http->setPost($k, $v);
        }
        $this->http->setPost('data', null);
        if (strlen($this->http->post('user_email')) == 0) {
            $status = "Empty";
            $data = array("field" => "user_email", "text" => "Empty field user email");
            return array($status, $data);
        }
        if (strlen($this->http->post('user_password')) == 0) {
            $status = "Empty";
            $data = array("field" => "user_password", "text" => "Empty field user password");
            return array($status, $data);
        }
        $server = $this->http->post('server_install_url');
        $this->toServer->request->setServer($server);
        $response = $this->toServer->register();
        if ($response->getStatus() == 'ok') {
            $dataResponse = $response->getResponseData();
            $config['Network_id'] = $dataResponse['network_id'];
            $config['Network_password'] = $dataResponse['network_password'];
            $config['Encryption_alg'] = $dataResponse['encryption_alg'];
            $config['Encryption_key'] = $dataResponse['encryption_key'];
            $config['Mess_salt'] = Random::Rand(9, rand(9, 18), 'luds');
            $config['Admin_login'] = $this->http->post('admin_login');
            $config['Admin_password'] = $this->http->post('admin_password');
            $config['Useragent'] = "Messor client 1b";

            File::clear(Path::INFO);
            File::write(Path::INFO, Parser::toSettingArray($dataResponse));

            File::clear(Path::USERCONF);
            File::write(Path::USERCONF, Parser::toSettingArray($config));
            $status = "Ok";
            $data = array("text" => $response->getResponseData('message'), 'config' => $config);
            return array($status, $data);
        }

        if ($response->getStatus() == 'error_auth' || $response->getStatus() == 'error') {
            $status = "Error";
            $data = array("text" => $response->getResponseData('message'));
            return array($status, $data);
        }
    }

    public function getLicenses()
    {
        if (!file_exists(Path::LICENSE)) {
            File::create(Path::LICENSE);
            $string = "Malware_cleaner = 0\n";
            $string .= "File_system_control = 0\n";
            $string .= "File_database_backup = 0";
            File::write(Path::LICENSE, $string);
        }
        return Parser::toArraySetting(File::read(Path::LICENSE));
    }

    public function acceptLicense($pluginName)
    {
        $licenses = Parser::toArraySetting(File::read(Path::LICENSE));
        $licenses[$pluginName] = 1;
        File::clear(Path::LICENSE);
        File::write(Path::LICENSE, Parser::toSettingArray($licenses));
        return true;
    }

    /** @return FSCheck */
    public function FSCheck()
    {
        try {
            // $FSCheck = new \Fscheck\FSCheck();
            $FSCheck = new FSCheck();
            return $FSCheck;
        } catch (\Error $e) {
            return false;
        }
    }

    /** @return FSControll */
    public function FSControll($currentThis)
    {
        try {
            $FSControll = new FSControll($currentThis);
            return $FSControll;
        } catch (\Error $e) {
            return false;
        }
    }

    /** @return FDBBackup */
    public function FDBBackup($currentThis)
    {
        try {
            $FDBBackup = new FDBBackup($currentThis);
            return $FDBBackup;
        } catch (\Error $e) {
            return false;
        }
    }

    /** @return MCleaner */
    public function MCleaner($path, $currentThis)
    {
        try {
            $MCleaner = new MCleaner($path, $currentThis);
            return $MCleaner;
        } catch (\Error $e) {
            return false;
        }
    }

    /**
     * Checking for Malware Cleaner Signature Updates
     *
     * @param MCleaner $MCleaner
     * @return bool
     */
    public function MCleanerCheckVersion($MCleaner)
    {
        $servers = $this->getServers();
        $server = new toServer($servers[0][0]);
        $object = $server->peerSignVersion();
        $version = $object->getResponseData('version');
        return $MCleaner->checkVersionSignature($version);
    }

    /**
     * Updating the signature version of Malware Cleaner
     *
     * @param MCleaner $MCleaner
     * @return void
     */
    public function MCleanerUpdateVersion($MCleaner)
    {
        $servers = $this->getServers();
        $server = new toServer($servers[0][0]);
        $object = $server->peerSignVersionUpgrade();
        $database = $object->getResponseData('database');
        $config = $MCleaner->getConfig();
        File::clear($config['SIGNATURE_FILE']);
        File::write($config['SIGNATURE_FILE'], $database);
    }

    /** @return SecuritySettings */
    public function SecuritySettings($path)
    {
        try {
            $SecuritySettings = new SecuritySettings($path);
            return $SecuritySettings;
        } catch (\Error $e) {
            return false;
        }
    }
}
