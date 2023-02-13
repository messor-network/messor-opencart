<?php

use messor\Autoloader;
use main\Adapter;
use messor\cms\Opencart;
use main\CronSettings;

class ControllerExtensionModuleMessor extends Controller
{
    use Opencart;
    use FileDatabaseBackup;
    use FileSystemControl;
    use FileSystemCheck;
    use MalwareClean;
    use SecuritySettings;

    private $adapter;
    private $error = array();
    private $free;
    private $version;

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->library('messor/Autoloader');
        $this->adapter = new Adapter();
        $this->free = true;
        $this->version = $this->adapter->MessorLib->getVersion();
    }

    public function Messor()
    {
        if (!$this->adapter->MessorLib->isConfig()) {
            $this->registerPage();
            return;
        }
        $this->addStyle(array('chunk-common.cf2ce3f1cf6882b2.css', 'main.f237f0c93dcd2c4b.css'));
        $this->addScript(array('chunk-vendors.ee4f6e34da3e61fe.js', 'chunk-common.cf2ce3f1cf6882b2.js', 'main.f237f0c93dcd2c4b.js'));

        $data['scripts'] = $this->getScript();
        $data['style'] = $this->getStyle();
        $this->setTitle("Messor security dashboard");
        $data['path_api'] = $this->getLinkApi('Api');
        $data['language'] = $this->getLanguage();
        $data['language'] = strip_tags(json_encode($data['language'], JSON_UNESCAPED_UNICODE));
        $data['header'] = $this->getHeader();
        $data['column_left'] = $this->getColumnLeft();
        $data['footer'] = $this->getFooter();
        $data['user_token'] = $this->getUserToken();
        $this->responseOutput("main/index", $data);
    }

    public function registerPage()
    {
        $this->addStyle(array('chunk-common.cf2ce3f1cf6882b2.css', 'register.2a6d958d6f385584.css'));
        $this->addScript(array('chunk-vendors.ee4f6e34da3e61fe.js', 'chunk-common.cf2ce3f1cf6882b2.js', 'register.2a6d958d6f385584.js'));

        $data['scripts'] = $this->getScript();
        $data['style'] = $this->getStyle();
        $data['path_api'] = $this->getLinkApi('Api');
        $data['language'] = $this->getLanguage();
        $data['language'] = strip_tags(json_encode($data['language'], JSON_UNESCAPED_UNICODE));
        $data['header'] = $this->getHeader();
        $data['column_left'] = $this->getColumnLeft();
        $data['footer'] = $this->getFooter();
        $data['user_token'] = $this->getUserToken();
        $this->responseOutput("install/register", $data);
    }

    public function Login()
    {
        if (!$this->adapter->MessorLib->isConfig()) {
            $this->registerPage();
            return;
        }
        $this->addStyle(array('chunk-common.29b78882d79f4499.css', 'register.66cb715e8ad6114a.css'));
        $this->addScript(array('chunk-vendors.d94906ca074f70f5.js', 'chunk-common.29b78882d79f4499.js', 'register.66cb715e8ad6114a.js'));

        $data['scripts'] = $this->getScript();
        $data['style'] = $this->getStyle();
        $data['path_api'] = $this->getLinkApi('Api');
        $data['language'] = $this->getLanguage();
        $data['language'] = strip_tags(json_encode($data['language'], JSON_UNESCAPED_UNICODE));
        $data['header'] = $this->getHeader();
        $data['column_left'] = $this->getColumnLeft();
        $data['footer'] = $this->getFooter();
        $data['user_token'] = $this->getUserToken();
        $this->responseOutput("login/index", $data);
    }
    public function archiveTable()
    {
        $this->addStyle('main.app.css');
        $this->addScript(array('main.app.js', 'main.chunk-vendors.js'));
        $data['scripts'] = $this->getScript();
        $data['style'] = $this->getStyle();
        $data['path_api'] = $this->getLinkApi('Api');
        $data['language'] = $this->getLanguage();
        $data['language'] = strip_tags(json_encode($data['language'], JSON_UNESCAPED_UNICODE));
        $data['header'] = $this->getHeader();
        $data['column_left'] = $this->getColumnLeft();
        $data['footer'] = $this->getFooter();
        $data['user_token'] = $this->getUserToken();
        $this->responseOutput("archive", $data);
    }

    public function ipBlackWhiteList()
    {
        $this->addStyle('main.app.css');
        $this->addScript(array('main.app.js', 'main.chunk-vendors.js'));
        $data['scripts'] = $this->getScript();
        $data['style'] = $this->getStyle();
        $data['path_api'] = $this->getLinkApi('Api');
        $data['language'] = $this->getLanguage();
        $data['language'] = strip_tags(json_encode($data['language'], JSON_UNESCAPED_UNICODE));
        $data['header'] = $this->getHeader();
        $data['column_left'] = $this->getColumnLeft();
        $data['footer'] = $this->getFooter();
        $data['user_token'] = $this->getUserToken();
        $this->responseOutput("table_blackwhite_list", $data);
    }

    public function fileDownload()
    {
        $file = $this->getRequestGet('file');
        $this->adapter->fileDownload($file);
    }

    public function Api()
    {
        $post = json_decode(file_get_contents('php://input'), true);
        $token = $this->getUserToken();
        switch ($post['action']) {
            case "register_data_page":
                $data = $this->adapter->ajaxRegisterDataPage();
                $data['user_token'] = $token;
                list($data['name'], $data['email']) = $this->getUserNameAndEmail();
                $data['op_version'] = $this->getVersion();
                $data['host'] = $this->getServerVar('HTTP_HOST');
                $data['success'] = $this->getUrlLink('Messor');
                $status = 'Ok';
                break;
            case "off_cloudflare":
                $status = $this->adapter->MessorLib->offCloudFlareAjax() ? 'Ok' : 'Error';
                $data = [];
                break;
            case "register":
                $post['cms'] = $this->getCMS();
                $post['cms_version'] = $this->getVersion();
                list($status, $data) = $this->adapter->MessorLib->register($post);
                break;
            case "main_data":
                $data = $this->adapter->Messor();
                $data['user_token'] = $token;
                $data['download_file'] = $this->getUrlLink('fileDownload');
                $data['ip_black'] = $this->getUrlLink('ipBlackWhiteList', array("type" => "black"));
                $data['ip_white'] = $this->getUrlLink('ipBlackWhiteList', array("type" => "white"));
                $data['sync_table'] = $this->getUrlLink('synchronizationTable');
                $data['archive_table'] = $this->getUrlLink('archiveTable');
                $status = 'Ok';
                break;
            case "synchronization":
                $data = $this->adapter->Synchronization();
                $status = $data ? 'Ok' : 'Error';
                $data = [];
                break;
            case "verification":
                list($status, $text) = $this->adapter->verify($post);
                $data = array('text' => $text);
                break;
            case "save_settings":
                $status = $this->adapter->saveSetting($post);
                $data = [];
                break;
            case "default_settings":
                $status = 'Ok';
                $data = $this->adapter->defaultSetting();
                break;
            case "black_white_list":
                list($status, $data) = $this->adapter->ipBlackWhiteList($post);
                $status = $data['ip_list'] ? 'Ok' : 'Empty';
                break;
            case "add_black_white_list":
                list($status, $data) = $this->adapter->ipBlackWhiteList($post);
                $status = $status ? 'Ok' : 'Error';
                $data = $data['ip_list'] ? $data : [];
                break;
            case "delete_black_white_list":
                list($status, $data) = $this->adapter->IpBlackWhiteList($post);
                $status = $status ? 'Ok' : 'Error';
                $data = $data['ip_list'] ? $data : [];
                break;
            case "validation_ip":
                $data['header'] = $this->getHeader();
                $data['column_left'] = $this->getColumnLeft();
                $data['footer'] = $this->getFooter();
                $status = $this->adapter->validationIp($post['ip']) ? 'Ok' : 'Error';
                $text = $status == 'Ok' ? 'Valid' : 'No valid';
                $data = array('text' => $text);
                break;
            case "archive_table":
                $data = $this->adapter->archiveSyncTable($post, 'archive');
                $status = $data['list_attack'] ? 'Ok' : 'Empty';
                $data = $data['list_attack'] ? $data : [];
                break;
            case "sync_table":
                $data = $this->adapter->archiveSyncTable($post, 'sync');
                $status = $data['list_attack'] ? 'Ok' : 'Empty';
                $data = $data['list_attack'] ? $data : [];
                break;
            case "item_sync_delete":
                $status = $this->adapter->MessorLib->deleteSyncItem($post['id']) ? 'Ok' : 'Error';
                $text = $status == 'Ok' ? 'Item was delete' : 'No delete';
                $data = array('text' => $text);
                break;
            case "item_sync_modal_delete":
                $status = $this->adapter->MessorLib->deleteSyncItemModal($post['id']) ? 'Ok' : 'Error';
                $text = $status == 'Ok' ? 'Item was delete' : 'No delete';
                $data = array('text' => $text);
                break;
            case "item_archive_modal_delete":
                $status = $this->adapter->MessorLib->deleteArchiveItemModal($post['id']) ? 'Ok' : 'Error';
                $text = $status == 'Ok' ? 'Item was delete' : 'No delete';
                $data = array('text' => $text);
                break;
            case "file_clean":
                $status = $this->adapter->MessorLib->clearFile($post['file']) ? 'Ok' : 'Error';
                $text = $status == 'Ok' ? 'File was delete' : 'No delete';
                $data = array('text' => $text);
                break;
            case "file_see":
                $data = $this->adapter->MessorLib->viewFile($post['file']);
                $status = $data ? 'Ok' : 'Empty';
                $data = $data ?: [];
                break;
            case "peer_info":
                $data = $this->adapter->MessorLib->getAboutPeer();
                $status = 'Ok';
                break;
            case "peer_info_of_server":
                $data = $this->adapter->MessorLib->getAboutPeerOfServer();
                $status = 'Ok';
                break;
            case "add_user_signature":
                if ($post['signature'] != null) {
                    $status = $this->adapter->MessorLib->addSignature($post['signature']) ? 'Ok' : 'Error';
                    $text = $status == 'Ok' ? 'File was save' : 'No save';
                } else {
                    $status = "Error";
                    $text = 'No save';
                }
                $data = array('text' => $text);
                break;
            case "delete_user_signature":
                $status = $this->adapter->MessorLib->deleteSignature($post['signature']) ? 'Ok' : 'Error';
                $text = $status == 'Ok' ? 'File was delete' : 'No delete';
                $data = array('text' => $text);
                break;
            case "get_user_signature":
                $data = $this->adapter->MessorLib->getSignature();
                $status = $data ? 'Ok' : 'Empty';
                $data = $data ?: [];
                break;
            case "update_user_signature":
                $status = $this->adapter->MessorLib->deleteSignature($post['old_signature']) ? 'Ok' : 'Error';
                $status = $this->adapter->MessorLib->addSignature($post['new_signature']) ? 'Ok' : 'Error';
                $text = $status == 'Ok' ? 'File was save' : 'No save';
                $data = array('text' => $text);
                break;
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array('status' => $status, 'data' => $data));
    }
}

trait FileSystemCheck
{
    public function FSCheckMain()
    {
        if (!$this->adapter->MessorLib->isConfig()) {
            $this->registerPage();
            return;
        }

        $this->setTitle("Messor File System Check");
        $this->addStyle(array('chunk-common.cf2ce3f1cf6882b2.css', 'filesystem-check.3200427302c61ae1.css'));
        $this->addScript(array('chunk-vendors.ee4f6e34da3e61fe.js', 'chunk-common.cf2ce3f1cf6882b2.js', 'filesystem-check.3200427302c61ae1.js'));

        $data['scripts'] = $this->getScript();
        $data['style'] = $this->getStyle();
        $data['path_api'] = $this->getLinkApi('FSCheckApi');
        $data['language'] = $this->getLanguage();
        $data['language'] = strip_tags(json_encode($data['language'], JSON_UNESCAPED_UNICODE));

        $data['header'] = $this->getHeader();
        $data['column_left'] = $this->getColumnLeft();
        $data['footer'] = $this->getFooter();
        $data['user_token'] = $this->getUserToken();

        $this->responseOutput("FSCheck/index", $data);
    }

    public function FSCheckResult()
    {
        $this->addStyle(array('main.app.css', 'file-system-check.app.css'));
        $this->addScript(array('file-system-check.app.js'));
        $data['scripts'] = $this->getScript();
        $data['style'] = $this->getStyle();
        $this->setTitle("Messor File System Check");
        $data['path_api'] = $this->getLinkApi('FSCheckApi');
        $data['language'] = $this->getLanguage();
        $data['language'] = strip_tags(json_encode($data['language'], JSON_UNESCAPED_UNICODE));

        $data['header'] = $this->getHeader();
        $data['column_left'] = $this->getColumnLeft();
        $data['footer'] = $this->getFooter();
        $data['user_token'] = $this->getUserToken();
        $this->responseOutput("FSCheck/index", $data);
    }

    public function FSCheckApi()
    {
        $post = json_decode(file_get_contents('php://input'), true);
        $token = $this->getUserToken();
        switch ($post['action']) {
            case "main":
                $FSCheck = $this->adapter->MessorLib->FSCheck();
                $data['path'] = $this->getDefaultPath();
                $data['result'] = $this->getUrlLink('FSCheckResult');
                $status = 'Ok';
                break;
            case "result":
                $status = 'Ok';
                $data = $this->adapter->FSCheckApiResult($post);
                $data['token'] = $token;
                break;
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array('status' => $status, 'data' => $data));
    }
}

trait FileSystemControl
{
    public function FSControlMain()
    {
        $this->setTitle("Messor File System Controll");

        if (!$this->adapter->MessorLib->isConfig()) {
            $this->registerPage();
            return;
        }

        $FSControll = $this->adapter->MessorLib->FSControll($this);

        $this->addStyle(array('chunk-common.cf2ce3f1cf6882b2.css', 'filesystem-control.387c561971f19fcc.css'));
        $this->addScript(array('chunk-vendors.ee4f6e34da3e61fe.js', 'chunk-common.cf2ce3f1cf6882b2.js', 'filesystem-control.387c561971f19fcc.js'));

        $data['scripts'] = $this->getScript();
        $data['style'] = $this->getStyle();
        $data['header'] = $this->getHeader();
        $data['column_left'] = $this->getColumnLeft();
        $data['footer'] = $this->getFooter();
        $data['path_api'] = $this->getLinkApi('FSControlApi');
        $data['language'] = $this->getLanguage();
        $data['language'] = strip_tags(json_encode($data['language'], JSON_UNESCAPED_UNICODE));

        $data['user_token'] = $this->getUserToken();

        if (!$FSControll) {
            $data['link'] = "https://messor.network/upgrade/OpenCart/#FsControll";
            $this->responseOutput("upgrade", $data);
            return;
        }


        $this->responseOutput("FSCcontroll/index", $data);
    }

    public function FSControlOneTime()
    {
        $this->setTitle("Messor File System Controll");
        $this->addStyle(array('main.app.css', 'file-system-control.app.css'));
        $this->addScript(array('file-system-control.app.js'));
        $data['scripts'] = $this->getScript();
        $data['style'] = $this->getStyle();
        $data['path_api'] = $this->getLinkApi('FSControlApi');
        $data['language'] = $this->getLanguage();
        $data['language'] = strip_tags(json_encode($data['language'], JSON_UNESCAPED_UNICODE));

        $data['header'] = $this->getHeader();
        $data['column_left'] = $this->getColumnLeft();
        $data['footer'] = $this->getFooter();
        $data['user_token'] = $this->getUserToken();

        $this->responseOutput("FSCcontroll/index", $data);
    }

    public function FSControlResult()
    {
        $this->addStyle('main.app.css', 'file-system-control.app.css');
        $this->addScript(array('file-system-control.app.js'));
        $data['scripts'] = $this->getScript();
        $data['style'] = $this->getStyle();
        $this->setTitle("Messor File System Controll");
        $data['path_api'] = $this->getLinkApi('FSControlApi');
        $data['language'] = $this->getLanguage();
        $data['language'] = strip_tags(json_encode($data['language'], JSON_UNESCAPED_UNICODE));

        $data['header'] = $this->getHeader();
        $data['column_left'] = $this->getColumnLeft();
        $data['footer'] = $this->getFooter();
        $data['user_token'] = $this->getUserToken();

        $this->responseOutput("FSCcontroll/index", $data);
    }


    public function FSControlApi()
    {
        $post = json_decode(file_get_contents('php://input'), true);
        $data['user_token'] = $this->getUserToken();
        switch ($post['action']) {
            case "main":
                $status = 'Ok';
                $FSControll = $this->adapter->MessorLib->FSControll(null);
                $data['path'] = $this->getDefaultPath();
                $data['result'] = $this->getUrlLink('FSControllResult');
                $data['one_time'] = $this->getUrlLink('FSControlOneTime');
                break;
            case "result":
                $status = 'Ok';
                $FSControll = $this->adapter->MessorLib->FSControll(null);
                $data = $this->adapter->FSControlApiResult($post);
                list($level, $result) = $FSControll->dataForNotify($data);
                $response = $this->adapter->MessorLib->notifyOnServer('fsc', $level, $result);
                break;
            case "exclude":
                $status = $this->adapter->FSControlApiExcludeFile($post) ? 'Ok' : 'Error';
                $text = $status == 'Ok' ? 'Item was exclude' : 'No exclude';
                $data = array('text' => $text);
                break;
            case "remove_of_exclude":
                $status = $this->adapter->FSControlApiRemoveFile($post) ? 'Ok' : 'Error';
                $text = $status == 'Ok' ? 'Item was remove' : 'No remove';
                $data = array('text' => $text);
                break;
            case "check_license":
                $status = $this->adapter->FSControlApiCheckLicense() ? 'Ok' : 'Error';
                $text = $status == 'Ok' ? true : false;
                $data = array('accepted' => $text);
                break;
            case "accept_license":
                $status = $this->adapter->FSControlApiAcceptLicense() ? 'Ok' : 'Error';
                $text = $status == 'Ok' ? true : false;
                $data = array('accepted' => $text);
                break;
            case "cron_data":
                $cronSettings = new CronSettings();
                $cronSettings->setFileControlPath($this->getDefaultPath());
                $data['cron_config'] = $cronSettings->getFileControlConfigOfFile();
                $status = 'Ok';
                $text = $status == 'Ok' ? true : false;
                break;
            case "cron_data_save":
                $cronSettings = new CronSettings();
                $response = $cronSettings
                    ->setFileControlPath($post['cron_path'])
                    ->setFileControlExclude($post['cron_exclude'])
                    ->saveFileControlConfig();
                $status = $response == true ? 'Ok' : 'Error';
                $data['cron_text_path'] = $cronSettings->getFileControlTextCronPath();
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array('status' => $status, 'data' => $data));
    }
}

trait FileDatabaseBackup
{
    public function FDBBMain()
    {
        if (!$this->adapter->MessorLib->isConfig()) {
            $this->registerPage();
            return;
        }

        $FDBBackup = $this->adapter->MessorLib->FDBBackup($this);

        $this->addStyle(array('chunk-common.cf2ce3f1cf6882b2.css', 'file-database-backup.22f1a13b73066842.css'));
        $this->addScript(array('chunk-vendors.ee4f6e34da3e61fe.js', 'chunk-common.cf2ce3f1cf6882b2.js', 'file-database-backup.22f1a13b73066842.js'));
        $data['scripts'] = $this->getScript();
        $data['style'] = $this->getStyle();
        $this->setTitle('Messor BackUp');
        $data['language'] = $this->getLanguage();
        $data['language'] = strip_tags(json_encode($data['language'], JSON_UNESCAPED_UNICODE));
        $data['path_api'] = $this->getLinkApi('FDBBApi');

        $data['header'] = $this->getHeader();
        $data['column_left'] = $this->getColumnLeft();
        $data['footer'] = $this->getFooter();
        $data['user_token'] = $this->getUserToken();

        if (!$FDBBackup) {
            $data['link'] = "https://messor.network/upgrade/OpenCart/#BackUp";
            $this->responseOutput("upgrade", $data);
            return;
        }

        $this->responseOutput("FDBB/index", $data);
    }

    public function FDBBApi()
    {
        $post = json_decode(file_get_contents('php://input'), true);
        $data['user_token'] = $this->getUserToken();
        switch ($post['action']) {
            case "main":
                $status = 'Ok';
                $database = $this->getDatabaseData();
                $data = $this->adapter->FDBBApiMain($database);
                $data['exclude'] = $this->adapter->FDBBApiGetExcludeFile();
                $data['email'] = $this->getEmail();
                $data['path'] = $this->getDefaultPath();
                $data['action'] = $this->getUrlLink('FDBBWeb');
                break;
            case "email":
                $database = $this->getDatabaseData();
                $FDBBackup = $this->adapter->MessorLib->FDBBackup(null);
                list($level, $result) = $FDBBackup->dataForNotify();
                $response = $this->adapter->MessorLib->notifyOnServer('fdbackup', $level, $result);
                $status = $this->adapter->FDBBApiResult($post, $database);
                $status = $status ? "Ok" : "Error";
                $data = $status ? "Send" : "Error send";
                break;
            case "download":
                $database = $this->getDatabaseData();
                $FDBBackup = $this->adapter->MessorLib->FDBBackup(null);
                list($level, $result) = $FDBBackup->dataForNotify();
                $response = $this->adapter->MessorLib->notifyOnServer('fdbackup', $level, $result);
                $status = $this->adapter->FDBBApiResult($post, $database);
                break;
            case "save":
                $database = $this->getDatabaseData();
                $FDBBackup = $this->adapter->MessorLib->FDBBackup(null);
                list($level, $result) = $FDBBackup->dataForNotify();
                $response = $this->adapter->MessorLib->notifyOnServer('fdbackup', $level, $result);
                $status = $this->adapter->FDBBApiResult($post, $database);
                $status = $status ? "Ok" : "Error";
                $data = $status ? "Save" : "Error save";
                break;
            case "exclude":
                $status = $this->adapter->FDBBApiExcludeFile($post) ? 'Ok' : 'Error';
                $text = $status == 'Ok' ? 'Item was exclude' : 'No exclude';
                $data = array('text' => $text);
                break;
            case "check_license":
                $status = $this->adapter->FDBBApiCheckLicense() ? 'Ok' : 'Error';
                $text = $status == 'Ok' ? true : false;
                $data = array('accepted' => $text);
                break;
            case "accept_license":
                $status = $this->adapter->FDBBApiAcceptLicense() ? 'Ok' : 'Error';
                $text = $status == 'Ok' ? true : false;
                $data = array('accepted' => $text);
                break;
            case "cron_data":
                $cronSettings = new CronSettings();
                $cronSettings->setDatabasePath($this->getDefaultPath());
                $data['cron_config'] = $cronSettings->getDatabaseConfigOfFile();
                $data['cron_exclude'] = $cronSettings->getDatabaseExcludeOfFile();
                $data['cron_database'] = $cronSettings->getDatabaseData();
                if ($data['cron_database'] == null) {
                    $database = $this->getDatabaseData();
                    $cronSettings->saveDatabaseData(
                        $database['host'],
                        $database['user'],
                        $database['password'],
                        $database['dbname']
                    );
                    $data['cron_database'] = $cronSettings->getDatabaseData();
                }
                $data['cron_tables'] = $cronSettings->getDatabaseTables();
                $status = 'Ok';
                $text = $status == 'Ok' ? true : false;
                break;
            case "cron_data_save":
                $cronSettings = new CronSettings();
                $cronSettings->saveDatabaseData(
                    $post['cron_database']['cron_host'],
                    $post['cron_database']['cron_user'],
                    $post['cron_database']['cron_password'],
                    $post['cron_database']['cron_dbname'],
                );
                $cronSettings->saveDatabaseTables($post['cron_tables']);
                $cronSettings->saveDatabaseExclude($post['cron_exclude']);
                $response = $cronSettings
                    ->setDatabaseAction($post['cron_config']['cron_action'])
                    ->setDatabasePath($post['cron_config']['cron_path'])
                    ->setDatabaseArchivation($post['cron_config']['cron_type_arch'])
                    ->setDatabaseTypeBackup($post['cron_config']['cron_type_backup'])
                    ->setDatabaseEmail($post['cron_config']['cron_email_user'])
                    ->setDatabaseSMTP(
                        $post['cron_config']['cron_smtp_url'],
                        $post['cron_config']['cron_smtp_port'],
                        $post['cron_config']['cron_smtp_login'],
                        $post['cron_config']['cron_smtp_password'],
                    )
                    ->saveDatabaseConfig();
                $status = $response == true ? 'Ok' : 'Error';
                $data['cron_text_path'] = $cronSettings->getDatabaseTextCronPath();
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array('status' => $status, 'data' => $data));
    }
}

trait MalwareClean
/* Модуль Malware Clean */
{
    public function MCLMain()
    {

        if (!$this->adapter->MessorLib->isConfig()) {
            $this->registerPage();
            return;
        }

        $MCleaner = $this->adapter->MessorLib->MCleaner($this->getDefaultPath(), $this);

        $this->setTitle("Messor Malware Сleaner");
        $data['path_api'] = $this->getLinkApi('MCLApi');
        $data['language'] = $this->getLanguage();
        $data['language'] = strip_tags(json_encode($data['language'], JSON_UNESCAPED_UNICODE));

        $this->addStyle(array('chunk-common.cf2ce3f1cf6882b2.css', 'malware-cleaner.9a504dfdaccf5b48.css'));
        $this->addScript(array('chunk-vendors.ee4f6e34da3e61fe.js', 'chunk-common.cf2ce3f1cf6882b2.js', 'malware-cleaner.9a504dfdaccf5b48.js'));
        $data['scripts'] = $this->getScript();
        $data['style'] = $this->getStyle();

        $data['header'] = $this->getHeader();
        $data['column_left'] = $this->getColumnLeft();
        $data['footer'] = $this->getFooter();
        $data['user_token'] = $this->getUserToken();

        if (!$MCleaner) {
            $data['link'] = "https://messor.network/upgrade/OpenCart/#MalwareCleaner";
            $this->responseOutput("upgrade", $data);
            return;
        }

        $result = $this->adapter->MessorLib->MCleanerCheckVersion($MCleaner);
        if (!$result) {
            $result = $this->adapter->MessorLib->MCleanerUpdateVersion($MCleaner);
        }

        $this->responseOutput('MCL/index', $data);
    }

    public function MCLApi()
    {
        $post = json_decode(file_get_contents('php://input'), true);
        $data['user_token'] = $this->getUserToken();
        switch ($post['action']) {
            case "main":
                $MCleaner = $this->adapter->MessorLib->MCleaner($this->getDefaultPath(), null);
                if ($MCleaner) {
                    $data = $this->adapter->MCLApiConfig($this->getDefaultPath());
                    $data['action'] = $this->getUrlLink('MCLResult');
                    $status = 'Ok';
                } else {
                    $status = "Error";
                    $data = null;
                }
                break;
            case "result":
                $MCleaner = $this->adapter->MessorLib->MCleaner(null, null);
                $status = 'Ok';
                $data = $this->adapter->MCLApiResult($post);
                list($level, $result) = $MCleaner->dataForNotify($data);
                $response = $this->adapter->MessorLib->notifyOnServer('cleaner', $level, $result);
                break;
            case "remove":
                $status = $this->adapter->MCLApiDeleteDangerFile($post) ? 'Ok' : 'Error';
                $text = $status == 'Ok' ? 'File was delete' : 'File not delete';
                $data = array('text' => $text);
                break;
            case "exclude":
                $status = $this->adapter->MCLApiExcludeDangerFile($post) ? 'Ok' : 'Error';
                $text = $status == 'Ok' ? 'Item was exclude' : 'No exclude';
                $data = array('text' => $text);
                break;
            case "remove_of_exclude":
                $status = $this->adapter->MCLApiRemoveFileOfExclude($post) ? 'Ok' : 'Error';
                $text = $status == 'Ok' ? 'Item was remove of exclude' : 'No remove of exclude';
                $data = array('text' => $text);
                break;
            case "check_license":
                $status = $this->adapter->MCLApiCheckLicense() ? 'Ok' : 'Error';
                $text = $status == 'Ok' ? true : false;
                $data = array('accepted' => $text);
                break;
            case "accept_license":
                $status = $this->adapter->MCLApiAcceptLicense() ? 'Ok' : 'Error';
                $text = $status == 'Ok' ? true : false;
                $data = array('accepted' => $text);
                break;
            case "cron_data":
                $cronSettings = new CronSettings();
                $cronSettings->setCleanerPath($this->getDefaultPath());
                $data['cron_config'] = $cronSettings->getCleanerConfigOfFile();
                $status = 'Ok';
                $text = $status == 'Ok' ? true : false;
                break;
            case "cron_data_save":
                $cronSettings = new CronSettings();
                $response = $cronSettings
                    ->setCleanerPath($post['Cron_PATH'])
                    ->setCleanerExtensionsPHP($post['Cron_EXTENSIONS_PHP'])
                    ->setCleanerExtensionsCGI($post['Cron_EXTENSIONS_CGI'])
                    ->setCleanerSignatureFile($post['Cron_SIGNATURE_FILE'])
                    ->setCleanerMaxNeedDetects($post['Cron_MAX_NEED_DETECTS'])
                    ->setCleanerMaxFilesizePHPEnable($post['Cron_MAX_FILESIZE_PHP_ENABLE'])
                    ->setCleanerMaxFilesizeCGIEnable($post['Cron_MAX_FILESIZE_CGI_ENABLE'])
                    ->setCleanerMaxFilesizeMB($post['Cron_MAX_FILESIZE_MB'])
                    ->setCleanerSignatureVersion($post['Cron_signature_version'])
                    ->setCleanerSignaturePHP($post['Cron_SIGNATURE_PHP'])
                    ->setCleanerSignatureCGI($post['Cron_SIGNATURE_CGI'])
                    ->setCleanerExcludeFiles($post['Cron_EXCLUDE_FILES'])
                    ->saveCleanerConfig();
                $status = $response == true ? 'Ok' : 'Error';
                $data['cron_text_path'] = $cronSettings->getCleanerTextCronPath();
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array('status' => $status, 'data' => $data));
    }
}

trait SecuritySettings
{
    public function SecuritySettingsMain()
    {
        $this->setTitle("Messor security settings");
        if (!$this->adapter->MessorLib->isConfig()) {
            $this->registerPage();
            return;
        }

        $this->addStyle(array('chunk-common.cf2ce3f1cf6882b2.css'));
        $this->addScript(array('chunk-vendors.ee4f6e34da3e61fe.js', 'chunk-common.cf2ce3f1cf6882b2.js', 'security-settings.be2fe29912988e41.js'));
        $data['scripts'] = $this->getScript();
        $data['style'] = $this->getStyle();
        $data['user_token'] = $this->getUserToken();
        $data['path_api'] = $this->getLinkApi('SecuritySettingsApi');
        $data['language'] = $this->getLanguage();
        $data['language'] = strip_tags(json_encode($data['language'], JSON_UNESCAPED_UNICODE));

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->responseOutput('SecuritySettings/index', $data);
    }

    public function SecuritySettingsApi()
    {
        $post = json_decode(file_get_contents('php://input'), true);
        $data['user_token'] = $this->getUserToken();
        switch ($post['action']) {
            case "main":
                $status = 'Ok';
                $post = array(
                    'prefix' => $this->getDBPrefix(),
                    'prefixForCMS' => $this->getDBPrefixForCMS(),
                    'configFileName' => $this->getConfigFileName(),
                    'DBUserName' => $this->getDBUserName(),
                    'path' => $this->getDefaultPath(),
                    'pathInstallDir' => $this->getPathInstallDir(),
                    'fullPath' => $this->getFullPath(),
                    'user' => $this->getUserCMS(),
                    'showError' => $this->getSettingsShowError(),
                    'adminPanelName' => $this->getAdminPanelName()
                );
                list($post['last_version'], $new_version) = $this->checkLastVersionCMS();
                $storage = $this->moveDirectoryStorage();
                $data = $this->adapter->SecuritySettingsApiMain($post, $storage);
                $data['last_version'] = $post['last_version'];
                $data['new_version'] = $new_version;
                $data['storage'] = $storage;
                break;
            case "exclude":
                $status = $this->adapter->MCLApiExcludeDangerFile($post) ? 'Ok' : 'Error';
                $text = $status == 'Ok' ? 'Item was exclude' : 'No exclude';
                $data = array('text' => $text);
                break;
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array('status' => $status, 'data' => $data));
    }
}
