<?php

namespace main;

use main\MessorLib;

/**
 * Adapter for interaction of the kernel with the web version of Messor
 */
class Adapter
{
    use MalwareClean;
    use FileSystemControl;
    use FileDatabaseBackup;
    use FileSystemCheck;
    use Registration;
    use SecuritySettings;

    public $MessorLib;
    private $error = array();
    private $free;

    /** Initialization */
    public function __construct()
    {
        $this->MessorLib = new MessorLib();
    }

    /**
     * Data for the main page of Messor Web
     *
     * @return array
     */
    public function Messor()
    {
        if ($this->MessorLib->isNewVersionMessor()) {
            $data['new_version'] = $this->MessorLib->txtNewVersionMessor();
        } else {
            $data['new_version'] = false;
        }

        $list = $this->MessorLib->getListSynchronization(true);
        $list = $this->MessorLib->decryptArray($list, array(2, 3, 6));
        if (!empty($list[0][0])) {
            $data['list_sync'] = $this->MessorLib->addIDInList($list);
        } else {
            $data['list_sync'] = array();
        }
        $data['list_sync_count'] = count($data['list_sync']);
        unset($data['list_sync']);

        $data['servers'] = $this->MessorLib->getServers();
        foreach ($data['servers'] as $key => $server) {
            $country = explode(',', $server[1]);
            $data['servers'][$key][] = $country[0];
            $data['servers'][$key][1] = $country[1];
            $host = parse_url($data['servers'][$key][0]);
            $data['servers'][$key][3] = $host['host'];
        }
        if ($this->MessorLib->isDatabase()) {
            $data['version_bd'] = $this->MessorLib->getVersionDatabase();
        } else {
            $this->Synchronization();
            $data['version_bd'] = $this->MessorLib->getVersionDatabase();
        }
        $data['list_archive'] = $this->MessorLib->getlistArchive();
        $data['list_archive_count'] = $data['list_archive'][0] != false ? count($data['list_archive']) : 0;
        unset($data['list_archive']);

        $data['on_cloudflare'] = $this->MessorLib->onCloudFlare();
        $data['off_cloudflare'] = $this->MessorLib->OffCloudFlare();
        $data['path'] = $this->MessorLib->getPathDBIP();
        $data['rules'] = $this->MessorLib->getRules();
        $data['settings'] = $this->MessorLib->getSetting();
        $data['error_log'] = $this->MessorLib->getErrorLog();
        $data['error_log_count'] = $data['error_log'][0] != false ? count($data['error_log']) : 0;
        unset($data['error_log']);
        $data['peer_log'] = $this->MessorLib->getPeerLog();
        $data['peer_log_count'] = $data['peer_log'][0] != false ? count($data['peer_log']) : 0;
        unset($data['peer_log']);
        $data['peer_info'] = $this->MessorLib->getAboutPeer();
        $data['last_sync'] = $this->MessorLib->lastSyncTime();
        $data['peer_list'] = $this->MessorLib->getPeerList();
        $data['peer_list_count'] = $data['peer_list'][0] != false ? count($data['peer_list']) : 0;
        unset($data['peer_list']);
        $data['database_ip'] = $this->MessorLib->getDatabaseIPList();
        $data['primary_server'] = $this->MessorLib->getPrimaryServer();
        $data['servers_hash'] = $this->MessorLib->getHashServerFile();
        $data['ip_white_list'] = $this->MessorLib->getListIP('white');
        $data['ip_white_list_count'] = count($data['ip_white_list']);
        unset($data['ip_white_list']);
        $data['ip_detect_list'] = $this->MessorLib->getListIP('detect');
        $data['ip_detect_count'] = count($data['ip_detect_list']);
        unset($data['ip_detect']);
        $data['file_clean'] = $this->MessorLib->checkFileSize();
        $data['config'] = $this->MessorLib->getConfigUser();
        return $data;
    }

    /**
     * Data for pages with attacks
     * Archive, Sync
     *
     * @param array $post
     * @param string $type
     * @return void
     */
    public function archiveSyncTable($post, $type)
    {
        $url = '';

        $sort = isset($post['sort']) ? $post['sort'] : 'ip_attack';
        $page = isset($post['page']) ? $post['page'] : 1;
        $sortDirection = isset($post['sort_direction']) ? $post['sort_direction'] : "DESC";
        $url .= $sortDirection == 'ASC' ? '&sort_direction=DESC' : '&sort_direction=ASC';

        if ($type == 'sync') {
            $listIP = $this->MessorLib->getListSynchronization(true);
        } else if ($type == 'archive') {
            $listIP = $this->MessorLib->getListArchive(true);
        }
        $listIP = $this->MessorLib->decryptArray($listIP, array(2, 3, 5));
        if (!empty($listIP[0][0])) {
            $listIP = $this->MessorLib->addIDInListModal($listIP);
        } else {
            $listIP = array();
        }
        $data['list_attack'] = $this->MessorLib->sortAttack($sort, $listIP, $sortDirection);
        if (isset($post['ip'])) {
            foreach ($data['list_attack'] as $k => $v)
                if ($v[0] != $post['ip']) {
                    unset($data['list_attack'][$k]);
                }
        }
        // foreach ($data['list_attack'] as $item) {
        //     $data['ip_list'][] = $item[0];
        // }
        $countnum = isset($data['list_attack']) ? count($data['list_attack']) : 0;
        list($data['list_attack'], $data['urls_pagination'], $data['num_page'], $data['prev'], $data['next']) = $this->MessorLib->pagination($countnum, 10, $page, $data['list_attack']);
        $data['sort_direction'] = $sortDirection;
        $data['sort'] = $sort;

        $data['type_sort'] = array('ip_attack', 'time_attack', 'url_attack', 'user_agent', 'type_attack', 'post');
        return $data;
    }

    /**
     * Data for pages with detect and allow lists
     *
     * @param array $post
     * @return array
     */
    public function ipBlackWhiteList($post)
    {
        $status = true;
        $page = isset($post['page']) ? $post['page'] : 1;
        $type = isset($post['type']) ? $post['type'] : 'black';

        $data['type'] = $type;
        $data['count_add_ip'] = 3;

        if (isset($post['ip1'])) {
            $list = array();
            for ($i = 1; $i <= $data['count_add_ip']; $i++) {
                switch ($post['time' . $i]) {
                    case "day": {
                            $post['time' . $i] = 1;
                            break;
                        }
                    case "week": {
                            $post['time' . $i] = 7;
                            break;
                        }
                    case "month": {
                            $post['time' . $i] = 31;
                            break;
                        }
                }
                if (!$this->validationIp($post['ip' . $i])) continue;
                $list[$post['ip' . $i]] = $post['time' . $i];
                if (!$post['ip' . $i]) {
                    unset($list[$post['ip' . $i]]);
                }
            }
            if (empty($list)) $status = false;
            if ($type == 'black') {
                $index = 0;
                foreach($list as $key => $value) {
                    $ipList[$index]['ip'] = $key; 
                    $ipList[$index]['day'] = $value;
                    $ipList[$index]['count'] = 0;
                    $index++;
                }
                $list = $ipList;
            }
            $this->MessorLib->addIP($type, $list);
        }

        $data['ip_list'] = $this->MessorLib->getListIP($type);
        if ($type == 'black') {
            $tmp = array();
            array_map(function($item) use (&$tmp) {
                $tmp[$item['ip']] = $item['day'];
            }, $data['ip_list']);
            $data['ip_list'] = $tmp;
        }

        if (isset($post['ip_delete']) && $post['ip_delete'] != null) {
            $listIp = $this->MessorLib->getListIP($type);
            $beforeCount = count($listIp);
            $data['result'] = $this->MessorLib->deleteIP($type, $listIp, $post['ip_delete']);
            $listIp = $this->MessorLib->getListIP($type);
            $afterCount = count($listIp);
            $data['ip_list'] = $listIp;
            $status = $beforeCount == $afterCount ? false : true;
        }

        if (isset($post['ip_search']) && $post['ip_search'] != null) {
            $listIp = $this->MessorLib->getListIP($type);
            $data['ip_list'] = $this->MessorLib->searchIP($listIp, $post['ip_search'], $type);
        }


        $countnum = isset($data['ip_list']) ? count($data['ip_list']) : 0;
        list($data['ip_list'], $data['urls_pagination'], $data['num_page'], $data['prev'], $data['next']) = $this->MessorLib->pagination($countnum, 7, $page, $data['ip_list']);
        $data['time'] = array('day', 'week', 'month', 'forever');
        return array($status, $data);
    }

    /**
     * Synchronizing the Messor client with the server
     *
     * @return void
     */
    public function Synchronization()
    {
        $res = $this->MessorLib->updateClient();
        $settings = $this->MessorLib->getSetting();
        $this->MessorLib->saveSetting($settings);
        return $res;
    }

    /**
     * Getting default settings
     *
     * @return array
     */
    public function defaultSetting()
    {
        $js_salt = $this->MessorLib->setJsSalt();
        return [
            'redirect_url' => '',
            'message' => "Your+IP+address+is+blocked+by+the+Messor+security+system+to+unblock%2C+contact+the+administrator+at+email+admin%40example.com+and+enter+your+IP+address",
            'block_page_email' => '',
            'block_page_phone' => '',
            'block_ip' => 1,
            'block_detect_list' => 1,
            'block_detect_count' => 3,
            'block_detect_days' => 2,
            'block_agent' => 1,
            'block_agent_attack' => 1,
            'block_agent_search_engines' => 0,
            'block_agent_social' => 0,
            'block_agent_tools' => 1,
            'block_agent_bots' => 0,
            'block_request' => 1,
            'block_request_get' => 1,
            'block_request_post' => 1,
            'block_request_cookie' => 1,
            'block_ddos' => 0,
            'js_salt' => $js_salt,
            'lock' => 'js_unlock'
        ];
    }

    /**
     * Saving settings
     *
     * @param array $data
     * @return string
     */
    public function saveSetting($data)
    {
        if (isset($data['action'])) {
            unset($data['action']);
        }
        $status = $this->MessorLib->saveSetting($data);
        if ($status) {
            $status = "Ok";
        } else {
            $status = "Error";
        }
        return $status;
    }

    /**
     * Peer verification in the Messor network
     *
     * @param array $data
     * @return array
     */
    public function verify($data)
    {
        $verify = $this->MessorLib->verify();
        $toServer = $this->MessorLib->toServer();
        $type = $data['type'];
        if ($type == "email_confirm" || $type == "sms_confirm") {
            $code = $data['code'];
            $response = $toServer->verify($verify->$type($code));
        } else {
            $response = $toServer->verify($verify->$type());
        }
        $text = $response->getResponseData('message');
        $text = str_replace("\n", "<br>", $text);
        $status = $response->getResponseData('status');
        return array($status, $text);
    }

    /**
     * Downloading files from the Messor admin panel
     *
     * @param string $file
     * @return void
     */
    public function fileDownload($file)
    {
        $file = BASE_PATH . $file;
        $paths = $this->MessorLib->getPathList();
        foreach ($paths as $item) {
            if ($file == $item) {
                if (file_exists($file)) {
                    if (ob_get_level()) {
                        ob_end_clean();
                    }
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename=' . basename($file));
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    readfile($file);
                    exit;
                }
            }
        }
    }

    public function validationIp($ip)
    {
        return $this->MessorLib->checkIP($ip);
    }
}

/**
 * Registering a new peer on the Messor network
 */
trait Registration
{
    /**
     * Getting settings for displaying on the registration page
     *
     * @return array
     */
    public function ajaxRegisterDataPage()
    {
        if (function_exists('openssl_encrypt') or function_exists('mcrypt_encrypt')) {
            $data['algorithm_encrypt'] = array('aes128' => 'AES-128', 'aes256' => 'AES-256', 'bf' => 'blowfish', 'rc4' => 'RC4');
        } else {
            $data['algorithm_encrypt'] = array('rc4' => 'RC4');
        }

        if (!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS'])) {
            $data['protocol'] = "https://";
        } else {
            $data['protocol'] = "http://";
        }

        $data['writeable'] = $this->MessorLib->isWriteable();
        $data['host'] = $_SERVER['HTTP_HOST'];
        $data['on_cloudflare'] = $this->MessorLib->onCloudFlare();
        $data['off_cloudflare'] = $this->MessorLib->offCloudFlare();
        $data['version_plugin'] = $this->MessorLib->versionPlugin();
        $data['admin_login'] = $this->MessorLib->generateLogin();
        $data['admin_password'] = $this->MessorLib->generatePassword();
        $data['network_password'] = $this->MessorLib->generatePassword();
        $data['country'] = $this->MessorLib->getCountryList();
        $data['servers'] = $this->MessorLib->getServers();
        $data['servers'] = $this->MessorLib->checkServersOnline($data['servers'], 2);
        return $data;
    }
}

/**
 * File System Check
 */
trait FileSystemCheck
{
    /**
     * Request to File System Check to get the result of the check
     *
     * @param array $post
     * @return void
     */
    public function FSCheckApiResult($post)
    {
        $page = isset($post['page']) ? $post['page'] : 1;
        $limit = isset($post['limit']) ? $post['limit'] : 10;
        $FSCheck = $this->MessorLib->FSCheck();
        if (isset($post['path'])) {
            $FSCheck->setPath($post['path']);
        } else {
            $FSCheck->setPath($FSCheck->getDefaultPath());
        }

        if (isset($post['exclude'])) {
            $exclude = array_map('trim', explode("\n", $post['exclude']));
            $FSCheck->setExclude($exclude);
        }

        $data['result'] = $FSCheck->getResult();
        $countnum = $data['result']['general'] != null ? count($data['result']['general']) : 0;
        if ($countnum != 0) {
        list(
            $data['result']['general'],
            $data['urls_pagination'],
            $data['num_page'],
            $data['prev'],
            $data['next']
        ) = $this->MessorLib->pagination($countnum, $limit, $page, $data['result']['general']);
        } else {
            $data['result']['general'] = [];
            $data['urls_pagination'] = [];
            $data['num_page'] = 0;
            $data['prev'] = 'disabled';
            $data['next'] = 'disabled';
        }
        $data['statistic'] = $FSCheck->getCountStatistic();
        return $data;
    }
}

/**
 * File System Control
 */
trait FileSystemControl
{
    /**
     * Retrieving File System Control Check Results
     *
     * @param array $post
     * @return array
     */
    public function FSControlApiResult($post)
    {
        $FSControll = $this->MessorLib->FSControll(null);

        if (!empty($post['exclude'])) {
            $data['exclude_user'] = $FSControll->addExcludeType($post['exclude']);
        } else {
            $data['exclude_user'] = null;
        }

        if (isset($post['path'])) {
            $path = $post['path'];
        } else {
            $path = $FSControll->getDefaultPath();
        }

        $FSControll->setPath($path);
        if (!empty($post['exclude'])) {
            $FSControll->setUserExclude($post['exclude']);
        }

        $start_time = microtime(1);
        $FSControll->load_shot();
        $shot = $FSControll->getShot();
        $FSControll->dirToArray($FSControll->getPath());
        $FSControll->Scan();
        $FSControll->write_shot();
        $work_time = round((microtime(1) - $start_time), 2);

        $data['shot'] = $shot;
        $data['path'] = $path;
        $data['result'] = $FSControll->getResult();
        $data['percent'] = $FSControll->Percent();
        $data['work_time'] = $work_time;
        return $data;
    }

    /**
     * Exclude files from scanning
     *
     * @param array $post
     * @return bool
     */
    public function FSControlApiExcludeFile($post)
    {
        $FSControll = $this->MessorLib->FSControll(null);
        return $FSControll->addExclude($post['exclude']) ?  true : false;
    }

    /**
     * Delete files from the list excluded for scanning
     *
     * @param array $post
     * @return void
     */
    public function FSControlApiRemoveFile($post)
    {
        $FSControll = $this->MessorLib->FSControll(null);
        return $FSControll->removeExclude($post['remove']) ?  true : false;
    }

    public function FSControlApiCheckLicense()
    {
        $license = $this->MessorLib->getLicenses();
        return $license["File_system_control"];
    }

    public function FSControlApiAcceptLicense()
    {
        $license = $this->MessorLib->acceptLicense("File_system_control");
        return $license;
    }
}

/**
 * File Database Backup
 */
trait FileDatabaseBackup
{
    /**
     * Getting information to display on the main page of the plugin
     *
     * @param array $database
     * @return array
     */
    public function FDBBApiMain($database)
    {
        $FDBBackup = $this->MessorLib->FDBBackup(null);
        $FDBBackup::initDataBase($database['host'], $database['user'], $database['password'], $database['dbname']);
        $data['filename'] = $FDBBackup->setDumpName();
        $data['avaliable_arch'] = $FDBBackup::avaliableArch();
        $data['tables'] = $FDBBackup::$dbquery->getTables();
        $data['setting'] = $FDBBackup->getSetting();
        $data['type_backup'] = ["backup_file", "backup_db"];
        return $data;
    }

    /**
     * The method creates a backup and performs one of the following actions: 
     * sending it to the mail, saving it on the server, downloading
     *
     * @param array $post
     * @param array $database
     * @return void
     */
    public function FDBBApiResult($post, $database)
    {
        $FDBBackup = $this->MessorLib->FDBBackup(null);
        if (!isset($post['filename'])) {
            $post['filename'] = $FDBBackup->setDumpName();
        }
        $FDBBackup::initDataBase($database['host'], $database['user'], $database['password'], $database['dbname']);
        $FDBBackup::initDump($post['type_arch'], $post['filename']);
        $FDBBackup::$dump->setBasePath($post['path']);
        if (isset($post['type_backup']) && $post['type_backup'] == "backup_db") {
            $res = $FDBBackup::createDumpDB($post['tables']);
            switch ($post['action']) {
                case 'download':
                    $FDBBackup::$dump->download($FDBBackup::$dump->filenameDumpDb);
                    $FDBBackup::$dump->remove($FDBBackup::$dump->filenameDumpDb);
                    break;
                    case 'email':
                        $FDBBackup->addSetting($post['smtp_url'], $post['smtp_port'], $post['smtp_login'], $post['smtp_password']);
                        $Mailer = $FDBBackup->setMailer($FDBBackup::$dump->filenameDumpDb, $FDBBackup::$dump->dump_path, $post);
                        $response = $Mailer->send();
                        $FDBBackup::$dump->remove($FDBBackup::$dump->filenameDumpDb);
                        return $response;
                    case 'save':
                        return $res;
                        break;
            }
        }
        if (isset($post['type_backup']) && $post['type_backup'] == "backup_file") {
            if ($post['type_arch'] == ".zip") {
                $res = $FDBBackup::createDumpFileBackupZip($post['path']);
            } else {
                $res = $FDBBackup::createDumpFileBackupTar($post['path']);
            }
            
            switch ($post['action']) {
                case 'download':
                    $FDBBackup::$dump->download($FDBBackup::$dump->filenameDumpFile);
                    $FDBBackup::$dump->remove($FDBBackup::$dump->filenameDumpDb);
                    break;
                    case 'email':
                        $FDBBackup->addSetting($post['smtp_url'], $post['smtp_port'], $post['smtp_login'], $post['smtp_password']);
                        $Mailer = $FDBBackup->setMailer($FDBBackup::$dump->filenameDumpDb, $FDBBackup::$dump->dump_path,  $post);
                        $Mailer->send();
                        $FDBBackup::$dump->remove($FDBBackup::$dump->filenameDumpDb);
                        break;
                    case 'save':
                        return $res;
                        break;
            }
        }
    }

    public function FDBBApiExcludeFile($post)
    {
        $FDBBackup = $this->MessorLib->FDBBackup(null);
        return $FDBBackup->addExclude($post['exclude']) ?  true : false;
    }

    public function FDBBApiGetExcludeFile()
    {
        $FDBBackup = $this->MessorLib->FDBBackup(null);
        return $FDBBackup->getExclude();
    }

    public function FDBBApiCheckLicense()
    {
        $license = $this->MessorLib->getLicenses();
        return $license["File_database_backup"];
    }

    public function FDBBApiAcceptLicense()
    {
        $license = $this->MessorLib->acceptLicense("File_database_backup");
        return $license;
    }
}

/**
 * Malware Clean
 */
trait MalwareClean
{
    /**
     * Getting Initial Malware Clean Settings
     *
     * @return array
     */
    public function MCLApiConfig($path)
    {
        $MCleaner = $this->MessorLib->MCleaner($path, null);
        $data['config'] = $MCleaner->GetConfig();
        return $data;
    }

    /**
     *
     * Getting the result of the Malware Clean scan
     * @param array $post
     * @return array
     */
    public function MCLApiResult($post)
    {
        $MCleaner = $this->MessorLib->MCleaner(null, null);
        $MCleaner->SetConfig("PATH",  $post['PATH']);
        $MCleaner->SetConfig("EXTENSIONS_PHP", explode(" ", $post['EXTENSIONS_PHP']));
        $MCleaner->SetConfig("EXTENSIONS_CGI", explode(" ", $post['EXTENSIONS_CGI']));
        $MCleaner->SetConfig("EXTENSIONS_CGI", explode(" ", $post['EXTENSIONS_CGI']));
        $MCleaner->SetConfig("MAX_FILESIZE_MB",  (int)$post['MAX_FILESIZE_MB']);
        if (!empty($post['MAX_NEED_DETECTS'])) $MCleaner->SetConfig("MAX_NEED_DETECTS", (int)$post['MAX_NEED_DETECTS']);
        if (!empty($post['MAX_FILESIZE_PHP_ENABLE'])) $MCleaner->SetConfig("MAX_FILESIZE_PHP_ENABLE", true);
        else $MCleaner->SetConfig("MAX_FILESIZE_PHP_ENABLE", false);
        if (!empty($post['MAX_FILESIZE_CGI_ENABLE'])) $MCleaner->SetConfig("MAX_FILESIZE_CGI_ENABLE", true);
        else $MCleaner->SetConfig("MAX_FILESIZE_CGI_ENABLE", false);
        if (!empty($post['EXCLUDE_FILES'])) {
            $MCleaner->SetConfig("EXCLUDE_FILES", array_map('trim', explode("\n", $post['EXCLUDE_FILES'])));
        }
        if (!empty($post['EXCLUDE_FILES'])) {
            $MCleaner->SetConfig("EXCLUDE_FILES", array_map('trim', explode("\n", $post['EXCLUDE_FILES'])));
        }
        if (!empty($post['SIGNATURE_PHP'])) {
            $MCleaner->SetSignatures("detect_php_user", $post['SIGNATURE_PHP']);
        }
        if (!empty($post['SIGNATURE_CGI'])) {
            $MCleaner->SetSignatures("detect_cgi_user", $post['SIGNATURE_CGI']);
        }
        $start_time = microtime(1);
        $MCleaner->Scan();
        $work_time = round((microtime(1) - $start_time), 2);
        $data['circle'] = $MCleaner->Percent();
        $data['work_time'] = $work_time;
        $data['result'] = $MCleaner->GetResult();
        $data['config'] = $MCleaner->GetConfig();
        $data['config']['signature_version'] = str_replace('Version', '', $data['config']['signature_version']);
        return $data;
    }

    /**
     * Removing a malicious file from the server
     *
     * @param array $post
     * @return bool
     */
    public function MCLApiDeleteDangerFile($post)
    {
        $MCleaner = $this->MessorLib->MCleaner(null, null);

        if (isset($post['remove'])) {
            return $MCleaner->delete($post['remove']) ?  true : false;
        }
    }

    /**
     * Excluding a file from scanning
     *
     * @param array $post
     * @return bool
     */
    public function MCLApiExcludeDangerFile($post)
    {
        $MCleaner = $this->MessorLib->MCleaner(null, null);

        if (isset($post['exclude'])) {
            return $MCleaner->exclude($post['exclude']) ?  true : false;
        }
    }

    public function MCLApiRemoveFileOfExclude($post)
    {
        $MCleaner = $this->MessorLib->MCleaner(null, null);

        if (isset($post['remove_of_exclude'])) {
            return $MCleaner->removeOfExclude($post['remove_of_exclude']) ?  true : false;
        }
    }

    public function MCLApiCheckLicense()
    {
        $license = $this->MessorLib->getLicenses();
        return $license["Malware_cleaner"];
    }

    public function MCLApiAcceptLicense()
    {
        $license = $this->MessorLib->acceptLicense("Malware_cleaner");
        return $license;
    }
}

/**
 * Security Settings
 */
trait SecuritySettings
{
    /**
     * Returns the result of the Security Settings check
     *
     * @param array $post
     * @return array
     */
    public function SecuritySettingsApiMain($post, $storage)
    {
        extract($post);
        $SecuritySettings = $this->MessorLib->SecuritySettings($path);
        $data['admin_panel'] = $SecuritySettings->checkAdminPanel($fullPath, $adminPanelName);
        $data['admin_login'] = $SecuritySettings->checkAdminLogin($user);
        $data['user'] = $user;
        list($data['perms'], $data['result']) = $SecuritySettings->checkConfigPerms($configFileName);
        $data['install_dir'] = $SecuritySettings->checkInstallDirectory($pathInstallDir);
        $data['prefix'] = $SecuritySettings->checkDBPrefix($prefix, $prefixForCMS);
        $data['show_error'] = $SecuritySettings->checkShowError($showError);
        $data['new_version_messor'] = $this->MessorLib->isNewVersionMessor();
        
        $count = array($post['last_version'], $data['admin_panel'], $data['admin_login'], $data['result'], $data['install_dir']);
        $data['count'] = 0;
        foreach ($count as $item) {
            if (!$item) {
                $data['count']++;
            }
        }
        if (!$data['prefix'] && !$data['show_error'] && !$data['new_version_messor'] && !$storage) {
            $data['count']++;
        }

        if ($data['count'] == 0) {
            $data['percent'] = 0;
        } else {
            $data['percent'] = 100 * ($data['count'] / 6);
        }

        return $data;
    }
}
