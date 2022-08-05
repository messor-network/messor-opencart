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
 * Библиотека для взимодействия CMS с ядром Messor
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
     * Возвращает список серверов
     *
     * @return array
     */
    public function getServers()
    {
        return Parser::toArrayTab(Parser::toArray(File::read(Path::SERVERS)));
    }

    /**
     * Возвращает Error логи
     *
     * @return array|null
     */
    public function getErrorLog()
    {
        return Parser::toArray(File::read(PATH::ERROR));
    }

    /**
     * Возвращает логи межпирового взаимодействия
     *
     * @return array|null
     */
    public function getPeerLog()
    {
        return Parser::toArray(File::read(PATH::PEER_LOG));
    }

    /**
     * Возвращает информацию о пире из файла
     *
     * @return array
     */
    public function getAboutPeer()
    {
        return Parser::toArraySetting(File::read(PATH::INFO));
    }

    /**
     * Возврвщает информацию о пире из сервера
     * и записывает в файл
     *
     * @return array
     */
    public function getAboutPeerOfServer()
    {
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

    /**
     * Возвращает список доступных пиров
     *
     * @return array
     */
    public function getPeerList()
    {
        return Parser::toArray(File::read(PATH::PEERS));
    }

    /**
     * Возврвщает время последней синхронизации
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
     * Очищает переданный файл
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
     * Возвращает файл для отображения
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
     * Возвращает список IP из базы Messor
     *
     * @return array
     */
    public function getDatabaseIPList()
    {
        return Parser::toArray(File::read(Path::DB_IPLIST));
    }

    /**
     * Возврващает хеш файла со списком серверов
     *
     * @return string
     */
    public function getHashServerFile()
    {
        return hash_file('sha256', Path::SERVERS);
    }

    /**
     * Возврващает список IP адресов с данными
     * которые пытались атаковать сервер
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
     * Возврващает список IP адресов с данными
     * которые пытались атаковать сервер
     * и были перенесены в архив
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
     * Получает список настроек
     *
     * @return array
     */
    public function getSetting()
    {
        return Parser::toArraySetting(File::read(Path::SETTINGS));
    }

    /**
     * Получает список системных настроек
     *
     * @return array
     */
    public function getSystemSetting()
    {
        return Parser::toArraySetting(File::read(Path::SYSTEM_SETTINGS));
    }

    /**
     * Получает список правил по которым
     * IP детектируется как опасный
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
     * Сохраняет настройки
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
     * Проверка на существования конфига пользователя
     * для взаимодейсвия с сервером Messor
     *
     * @return bool
     */
    public function isConfig()
    {
        return !empty(file_get_contents(Path::USERCONF));
    }

    /**
     * Проверка на существование базы Messor
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
     * Проверка новой версии клиента Messor
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
     * Возвращает версию клиента Messor
     *
     * @return void
     */
    public function versionPlugin()
    {
        return Path::VERSION;
    }

    /**
     * Получает текст для вывода на клиент
     * Messor о новой версии клиента
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
     * Проверяет включен ли CloudFlare на сервере
     * в случае положительного результата включает
     * обработку IP адреса совместимую
     * с CloudFlare для Messor
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
     * Проверяет включен ли CloudFlare на сервере
     * в случает отрицательного результата и
     * включенной совместимости CloudFlare c Messor
     * возвращает true иначе false
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
     * Отключает совместимость CloudFlare c Messor
     * в системных настройках
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
     * Возвращает список файлов для которых
     * возможно получить логи
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
     * Список файлов базы данных в разных
     * форматах
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
     * Список доступных файлов для просмотра
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
     * Добавляет поле id в список
     * для возможности удаления 
     * элемента по его id
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
     * Удаляет элемент из списка
     * по его id
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
     * Добавляет поле id в список
     * для возможности удаления 
     * элемента по его id
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
     * Удаляет элемент из списка
     * по его id
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
     * Декодирует элементы массива
     * по ключам в переменной $keys
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
     * Получает IP адрес, если CloudFlare
     * включен, пробует получить HTTP_CF_CONNECTING_IP
     * после проверяет полученный IP, если пустой
     * получает REMOTE_ADDR
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
     * Устанавливает сервер для запросов
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
     * Проверка ключа переданного пиром
     * если ключ валидный то пир может принять
     * запрос
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
     * Сверяет ключ с хешем из файла, если
     * ключи совпадают то проходит разблокировка
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

    /**
     * Проверяет существования файла с хешем
     * для переданного IP адреса
     *
     * @param string $ip
     * @return bool
     */
    public function existHashIP($ip)
    {
        return file_exists(PATH::IPHASH . $ip);
    }

    /**
     * Добавляет хеш для IP адреса в файл
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
     * Удаляет все файлы с хешами для IP
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
     * Проверка серверов online|offline
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
     * Устанваливает Salt
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
     * Проверяет размеры файлов с логами
     * при привышение размера возвращает
     * название файла
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
     * Проверяет IP адрес на валидность
     * или что IP адрес является валидной
     * подсетью
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
     * Получает конфиг пользователя сети Messor
     *
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
     * Передаёт массив с атаками в сортировку
     * согласно переданному параметру $name
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
     * Переводит Unix time время в дату
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
     * Сортирует массив
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
     * Возвращает данные для пагинации
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
     * Добавляет IP адрес в allow
     * или detect список
     *
     * @param string $type
     * @param array $newlist
     * @return bool
     */
    public function addIP($type, $newlist)
    {
        $oldlist = $this->getListIP($type);
        $list = array_merge($oldlist, $newlist);
        return $this->setListIP($type, $list);
    }

    /**
     * Удаляет IP адрес allow
     * или detect списка
     *
     * @param string $type
     * @param array $list
     * @param string $ip
     * @return bool
     */
    public function deleteIP($type, $list, $ip)
    {
        foreach ($list as $key => &$value) {
            if ($key == $ip) {
                unset($list[$ip]);
            }
        }
        return $this->setListIP($type, $list);
    }

     /**
     * поиск IP адрес allow
     * или detect списка
     *
     * @param string $type
     * @param array $list
     * @param string $ip
     * @return bool
     */
    public function searchIP($list, $ip)
    {
        foreach ($list as $key => $value) {
            if ($key == $ip) {
                $list = array($ip => $value);
                return $list;
            }
        }
        $list = array('null' => null);
        return $list;
    }

    /**
     * Возвращает список IP адресов из allow
     * или detect списка
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
            $result = Parser::toArraySetting(File::read(Path::DETECT_LIST));
            if ($result)
                return $result;
            else return array();
        }
    }

    /**
     * Перезаписывает файл с IP адресами
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
            return (File::write(Path::DETECT_LIST, Parser::toSettingArray($list)));
        }
    }

    /**
     * Перезаписывает файл с аттаками
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
     * Перезаписывает файл с аттаками
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
     * Перезаписывает файл с аттаками в архиве
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
     * Удаляет атаку из списка с атаками
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
     * Удаляет атаку из списка с атаками из архива
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
     * Удаляет атаку из списка с атаками
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
     * Генерирует логин для Messor
     *
     * @return string
     */
    public function generateLogin()
    {
        return Random::Rand(4, 7, "lu");
    }

    /**
     * Генерирует пароль для Messor
     *
     * @return string
     */
    public function generatePassword()
    {
        return Random::Rand();
    }

    /**
     * Возвращает список стран для регистрации
     *
     * @return array
     */
    public function getCountryList()
    {
        return Parser::toArraySetting(File::read(BASE_PATH . "/data/install/country_list.txt"));
    }

    /**
     * Проверяет время создания файла
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
     * Удаляет 1 единицу из файла с детектами
     * для IP адреса, когда IP будет равным 0, адрес
     * удалится из детект списка
     *
     * @return void
     */
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

    /**
     * Проверяет и возвращает текст на обновление Messor
     *
     * @return string
     */
    public function newVersion()
    {
        if (filesize(Path::UPDATE)) {
            $file = File::read(Path::UPDATE);
            $file = htmlspecialchars($file);
            $file = str_replace("\n", "</br>", $file);
            return $file;
        }
    }

    /**
     * Регистрация пользователя в сети Messor
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
     * Проверка обновлений сигнатур Malware Cleaner
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
     * Обновление версии сигнатур Malware Cleaner
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
