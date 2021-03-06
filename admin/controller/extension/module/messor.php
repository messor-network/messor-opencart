<?php

use messor\Autoloader;
use main\Adapter;
use messor\cms\Opencart;

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
        $this->addStyle('main.app.css');
        $this->addScript(array('main.app.js', 'main.chunk-vendors.js'));
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
        $this->addStyle('install.app.css');
        $this->addScript(array('install.app.js', 'install.chunk-vendors.js'));
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
            case "validation_ip":$data['header'] = $this->getHeader();
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
        $this->addStyle(array('main.app.css', 'file-system-check.app.css'));
        $this->addScript(array('file-system-check.app.js'));
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

        $FSControll = $this->adapter->MessorLib->FSControll($this);
        if (!$this->adapter->MessorLib->isConfig()) {
            $this->registerPage();
            return;
        }

        $this->addStyle(array('main.app.css', 'file-system-control.app.css'));
        $this->addScript(array('file-system-control.app.js'));
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
                $data['path'] = $FSControll->getDefaultPath();
                $data['result'] = $this->getUrlLink('FSControllResult');
                $data['one_time'] = $this->getUrlLink('FSControlOneTime');
                break;
            case "result":
                $status = 'Ok';
                $data = $this->adapter->FSControlApiResult($post);
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
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array('status' => $status, 'data' => $data));
    }
}

trait FileDatabaseBackup
{
    public function FDBBMain()
    {
        
        $FDBBackup = $this->adapter->MessorLib->FDBBackup($this);
        if (!$this->adapter->MessorLib->isConfig()) {
            $this->registerPage();
            return;
        }

        $this->addStyle(array('main.app.css', 'file-database-backup.app.css'));
        $this->addScript(array('file-database-backup.app.js'));
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
                $post['email'] = $this->getEmail();
                $database = $this->getDatabaseData();
                $post['default_path'] = $this->getDefaultPath();
                $status = $this->adapter->FDBBApiResult($post, $database);
                $status = $status ? "Ok" : "Error";
                $data = $status ? "Send" : "Error send";
                break;
            case "download":
                $post['default_path'] = $this->getDefaultPath();
                $database = $this->getDatabaseData();
                $status = $this->adapter->FDBBApiResult($post, $database);
                break;
            case "save":
                $post['default_path'] = $this->getDefaultPath();
                $database = $this->getDatabaseData();
                $status = $this->adapter->FDBBApiResult($post, $database);
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
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array('status' => $status, 'data' => $data));
    }
}

trait MalwareClean
/* ???????????? Malware Clean */
{
    public function MCLMain()
    {
        $MCleaner = $this->adapter->MessorLib->MCleaner($this->getDefaultPath(), $this);

        if (!$this->adapter->MessorLib->isConfig()) {
            $this->registerPage();
            return;
        }

        $this->setTitle("Messor Malware ??leaner");
        $data['path_api'] = $this->getLinkApi('MCLApi');
        $data['language'] = $this->getLanguage();
        $data['language'] = strip_tags(json_encode($data['language'], JSON_UNESCAPED_UNICODE));

        $this->addStyle(array('main.app.css', 'malware-cleaner.app.css', 'subscription-alert-styles.css'));
        $this->addScript('malware-cleaner.app.js');
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


    public function MCLResult()
    {
        $this->addStyle(array('main.app.css', 'malware-cleaner.app.css'));
        $this->addScript('malware-cleaner.app.js');
        $data['scripts'] = $this->getScript();
        $data['style'] = $this->getStyle();
        $this->setTitle("Messor Malware ??leaner ");
        $data['path_api'] = $this->getLinkApi('MCLApi');
        $data['language'] = $this->getLanguage();
        $data['language'] = strip_tags(json_encode($data['language'], JSON_UNESCAPED_UNICODE));

        $data['header'] = $this->getHeader();
        $data['column_left'] = $this->getColumnLeft();
        $data['footer'] = $this->getFooter();
        $data['user_token'] = $this->getUserToken();

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
                $status = 'Ok';
                $data = $this->adapter->MCLApiResult($post);
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

        $this->addStyle(array('main.app.css', 'security-settings.app.css'));
        $this->addScript(array('security-settings.app.js'));
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
                $storage = $this->moveDirectoryStorage();
                $data = $this->adapter->SecuritySettingsApiMain($post, $storage);
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
