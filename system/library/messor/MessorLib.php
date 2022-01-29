<?php

namespace messor;

require_once 'Autoloader.php';

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


final class MessorLib
{
    const VERSION_PLUG = "0.1b";

    private $registry;
    private $toServer;
    private $MCleaner;

    public function __construct()
    {
        $this->http = new HttpRequest();
        $this->toServer = new toServer(null);
        $this->toServer->request->setServer($this->getPrimaryServer());
    }

    public function checkUpdateDay($hour, $file)
    {

        if (time() - filemtime($file) <= ($hour * 60 * 60)) {
            return false;
        } else {
            return true;
        }
    }

    public function requestToPeer($key)
    {
        $info = Parser::toArraySetting(File::read(Path::INFO));
        if ($info['url_key'] == $key) {
            Peer::startForPlugins();
        }
    }

    public function updateClient()
    {
        Client::update();
    }

    public function versionPlugin()
    {
        return self::VERSION_PLUG;
    }

    public function isConfig()
    {
        return file_exists(Path::USERCONF);
    }

    public function toServer()
    {
        return new toServer($this->getPrimaryServer());
    }

    public function register($data)
    {
        foreach ($data as $item) {
            $this->http->setPost($item['name'], $item['value']);
        }
        $this->http->setPost('data', null);
        if (strlen($this->http->post('user_email')) == 0) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array('status' => 'EMPTY', 'text' => "Empty field user email"));
            die();
        }

        if (strlen($this->http->post('user_password')) == 0) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array('status' => 'EMPTY', 'text' => "Empty field user password"));
            die();
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
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array('status' => 'OK', 'text' => $response->getResponseData('message'), 'config' => $config));
            die();
        }

        if ($response->getStatus() == 'error_auth' || $response->getStatus() == 'error') {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array('status' => 'ERROR', 'text' => $response->getResponseData('message')));
            die();
        }
    }

    public function generateLogin()
    {
        return Random::Rand(4, 7, "lu");
    }

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

    public function generatePassword()
    {
        return Random::Rand();
    }

    public function check($ip, $disableDetect, $route,  $url = null)
    {
        Messor::check($ip, $disableDetect, $url, $route);
    }

    public function getSetting()
    {
        return Parser::toArraySetting(File::read(Path::SETTINGS));
    }

    public function setJsSalt()
    {
        return Random::Rand(7, 12);
    }

    public function saveSetting($setting)
    {
        unset($setting['module_messor_status']);
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

    public function verify()
    {
        return new Verify;
    }

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



    public function listArchive($tab = false)
    {
        if (!$tab) {
            return $this->listArchive = Parser::toArray(File::read(Path::ARCHIVE));
        } else {
            return $this->listArchive = Parser::toArrayTab(Parser::toArray(File::read(Path::ARCHIVE)));
        }
    }

    public function listSynchronization($tab = false)
    {
        if (!$tab) {
            return $this->listSync = Parser::toArray(File::read(Path::SYNC_LIST));
        } else {
            return $this->listSync = Parser::toArrayTab(Parser::toArray(File::read(Path::SYNC_LIST)));
        }
    }


    public function getDatabaseIP()
    {
        return Parser::toArray(File::read(Path::DB_IPLIST));
    }

    public function getCountryList()
    {
        return Parser::toArraySetting(File::read(BASE_PATH . "/data/install/country_list.txt"));
    }

    public function getServers()
    {
        return Parser::toArrayTab(Parser::toArray(File::read(Path::SERVERS)));
    }

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



    public function getListIP($type)
    {
        if ($type == "white") {
            $result = Parser::toArraySetting(File::read(Path::WHITE_LIST));
            if ($result) return $result;
            else return array();
        } else {
            $result = Parser::toArraySetting(File::read(Path::DETECT_LIST));
            if ($result)
                return $result;
            else return array();
        }
    }

    public function setListIP($type, $list)
    {
        if ($type == "white") {
            File::clear(PATH::WHITE_LIST);
            return (File::write(Path::WHITE_LIST, Parser::toSettingArray($list)));
        } else {
            File::clear(PATH::DETECT_LIST);
            return (File::write(Path::DETECT_LIST, Parser::toSettingArray($list)));
        }
    }

    public function getConfig()
    {
        $version = Path::VERSION;
        $conf = Parser::toArraySetting(File::read(Path::USERCONF));
        $conf['version'] = $version;
        return $conf;
    }

    public function setListSync($list)
    {
        File::clear(PATH::SYNC_LIST);
        return (File::write(Path::SYNC_LIST, Parser::toString($list)));
    }

    public function setListArchive($list)
    {
        File::clear(PATH::ARCHIVE);
        return (File::write(Path::ARCHIVE, Parser::toString(Parser::toStringTab($list))));
    }

    public function setListSyncMod($list)
    {
        File::clear(PATH::SYNC_LIST);
        return (File::write(Path::SYNC_LIST, Parser::toString(Parser::toStringTab($list))));
    }

    public function sorting($list, $sortDirection)
    {
        natsort($list);
        if ($sortDirection == "DESC") {
            return array_reverse($list);
        }
        return $list;
    }

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

    public function newVersion()
    {
        if (filesize(Path::UPDATE)) {
            $file = File::read(Path::UPDATE);
            $file = htmlspecialchars($file);
            $file = str_replace("\n", "</br>", $file);
            return $file;
        }
    }

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

    public function checkIP($item)
    {
        if (Check::ipValid($item) || Check::ipIsNet($item)) {
            return true;
        } else {
            return false;
        }
    }

    public function pagination($total, $limit, $page, $list, $url)
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
                $listUrl[$i] = str_replace('{page}', $i, $url);
            }
        } else {
            $begin = $page <= 2 ? 1 : $page - 2;
            $last = $page < $numPage - 2 ? $page + 2 : $numPage;
            for ($i = $begin, $j = $last; $i < $page, $page <= $j; $i++, $j--) {
                $listUrl[$i] = str_replace('{page}', $i, $url);
                $listUrl[$j] = str_replace('{page}', $j, $url);
                if ($i + 1 == $j - 1) {
                    $listUrl[$j - 1] = str_replace('{page}', $j - 1, $url);
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

    public function addIP($type, $newlist)
    {
        $oldlist = $this->getListIP($type);
        $list = array_merge($oldlist, $newlist);
        $this->setListIP($type, $list);
    }

    public function deleteIP($type, $list, $ip)
    {
        foreach ($list as $key => &$value) {
            if ($key == $ip) {
                unset($list[$ip]);
            }
        }
        $this->setListIP($type, $list);
    }

    public function deleteSyncItem($id)
    {
        $list = $this->listSynchronization();
        $list = $this->addID($list);
        foreach ($list as $i => $item) {
            if ($item['id'] == $id) {
                unset($list[$i]);
                break;
            }
        }
        $list = $this->deleteID($list);
        return $this->setListSync($list);
    }

    public function deleteArchiveItemModal($id)
    {
        $list = $this->listArchive(true);
        $list = $this->addIDModal($list);
        foreach ($list as $i => $item) {
            if ($item['id'] == $id) {
                unset($list[$i]);
                break;
            }
        }
        $list = $this->deleteIDModal($list);
        return $this->setListArchive($list);
    }

    public function deleteSyncItemModal($id)
    {
        $list = $this->listSynchronization(true);
        $list = $this->addIDModal($list);
        foreach ($list as $i => $item) {
            if ($item['id'] == $id) {
                unset($list[$i]);
                break;
            }
        }
        $list = $this->deleteIDModal($list);
        return $this->setListSyncMod($list);
    }

    public function addID($list)
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

    public function addIDModal($list)
    {
        $i = 0;
        foreach ($list as &$item) {
            $item['id'] = $i;
            $i++;
        }
        return $list;
    }

    public function deleteID($list)
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

    public function deleteIDModal($list)
    {
        foreach ($list as &$item) {
            unset($item['id']);
        }
        return $list;
    }

    public function getRules()
    {
        $rules = File::read(Path::RULES);
        $rules = unserialize($rules);
        return $rules;
    }

    public function getPath()
    {
        $path = array();
        $path['iplist'] = Path::DB_IPLIST;
        $path['iptables'] = Path::DB_IPTABLES;
        $path['path'] = Path::DB_HTACCESS;
        return $path;
    }

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

    public function viewFile($file)
    {
        $list = $this->getPathList();
        foreach ($list as $key => $value) {
            if ($key == "SYNC_LOG") {
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

    public function clearFile($file)
    {
        return File::clear($file);
    }

    public function getErrorLog()
    {
        return Parser::toArray(File::read(PATH::ERROR));
    }

    public function getPeerLog()
    {
        return Parser::toArray(File::read(PATH::PEER_LOG));
    }

    public function getConfPeer()
    {
        $peerConf = Parser::toArraySetting(File::read(Path::USERCONF));
        return $peerConf;
    }

    public function getAboutPeerOfServer()
    {
        $response = $this->toServer->info();
        File::clear(PATH::INFO);
        File::write(PATH::INFO, Parser::toSettingArray($response->getResponseData()));
        return $response->getResponseData();
    }

    public function getAboutPeer()
    {
        return Parser::toArraySetting(File::read(PATH::INFO));
    }

    public function getPeerList()
    {
        return Parser::toArray(File::read(PATH::PEERS));
    }


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

    public function deleteScoresDetect()
    {
        $day = 1;
        $newDetectList = array();
        if (filesize(PATH::DETECT_LIST)) {
            $detectList = Parser::toArraySetting(File::read(Path::DETECT_LIST));
            foreach ($detectList as $key => $value) {
                if ($value != "forever") {
                    $newDetectList[$key] = $value - ($day * 2);
                    if ($newDetectList[$key] <= 0) {
                        unset($newDetectList[$key]);
                    }
                }
            }
            File::clear(Path::DETECT_LIST);
            File::write(Path::DETECT_LIST, Parser::toSettingArray($newDetectList));
        }
    }

    public function getHashServerFile()
    {
        return hash_file('sha256', Path::SERVERS);
    }

    public function lastSync()
    {
        return date('d.m.Y', File::read(PATH::SYNC_LAST));
    }

    public function isDatabase()
    {
        if (!File::read(PATH::VERSION_BD)) {
            return false;
        } else {
            return true;
        }
    }

    public function versionDatabase()
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

    public function fileClear($file)
    {
        File::clear($file);
    }

    public function hashJs($key)
    {
        $setting = $this->getSetting();
        $systemSetting = Parser::toArraySetting(File::read(Path::SETTINGS));
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
            }
            $detect_list = Parser::toArraySetting(File::read(Path::DETECT_LIST));
            if (isset($detect_list[$ip])) {
                File::write(Path::WHITE_LIST, $ip . " = " . "3" . "\n");
            } else if ($setting["lock"] == "js_unlock" && $setting["block_ddos"] != 1) {
                File::write(Path::WHITE_LIST, $ip . " = " . "1" . "\n");
            }
            unset($detect_list[$ip]);
            File::clear((Path::DETECT_LIST));
            if ($detect_list != null) {
                File::write(Path::DETECT_LIST, Parser::toSettingArray($detect_list));
            }
        }
        return true;
    }

    public function MCleaner()
    {
        $MCleaner = new MCleaner();
        return $MCleaner;
    }

    public function MCleanerCheckVersion($MCleaner)
    {
        $object = $this->toServer->peerSignVersion();
        $version = $object->getResponseData('version');
        return $MCleaner->checkVersionSignature($version);
    }

    public function MCleanerUpdateVersion($MCleaner)
    {
        $object = $this->toServer->peerSignVersionUpgrade();
        $database = $object->getResponseData('database');
        $config = $MCleaner->getConfig();
        File::clear($config['SIGNATURE_FILE']);
        File::write($config['SIGNATURE_FILE'], $database);
    }

    public function FSControll()
    {
        $FSControll = new FSControll();
        return $FSControll;
    }

    public function FDBBackup()
    {
        $FDBBackup = new FDBBackup();
        return $FDBBackup;
    }

    public function FSCheck()
    {
        $FSCheck = new FSCheck();
        return $FSCheck;
    }

    public function SecuritySettings()
    {
        $SecuritySetting = new SecuritySettings();
        return $SecuritySetting;
    }
}
