<?php

use messor\Autoloader;
use messor\MessorLib;

class ControllerExtensionModuleMessor extends Controller
{
    use MalwareClean;
    use FileSystemControl;
    use FileDatabaseBackup;
    use FileSystemCheck;
    use OpencartSystem;
    use Registration;
    use SecuritySettings;

    private $MessorLib;
    private $error = array();
    private $free;

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->library('messor/Autoloader');
        $this->load->library('messor/MessorLib');
        $this->MessorLib = new MessorLib();
        $this->free = true;
    }

    public function index()
    {
        $this->response->redirect($this->url->link('extension/module/messor/Messor', 'user_token=' . $this->session->data['user_token'], true));
    }


    public function SynchronizationBeforeRemove()
    {
        $this->MessorLib->updateClient();
    }

    public function Synchronization()
    {
        $this->MessorLib->updateClient();
        $settings = $this->MessorLib->getSetting();
        $this->MessorLib->saveSetting($settings);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array('status' => 'OK'));
    }

    public function SynchronizationNoJS()
    {
        $this->MessorLib->updateClient();
        $settings = $this->MessorLib->getSetting();
        $this->MessorLib->saveSetting($settings);
    }

    public function saveSetting()
    {
        $status = $this->MessorLib->saveSetting($this->request->post);
        if ($status) {
            $status = "OK";
            $message = "Настройки сохранены";
        } else {
            $status = "ERROR";
            $message = "Ошибка, проверьте права на запись";
        }

        $this->session->data['success'] = 'Настройки сохранены';
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array('status' => $status, 'text' => $message));
    }


    public function ajaxModalServerListCheck()
    {
        if (isset($this->request->post['server'])) {
            $server[] = $this->request->post['server'];
            $response = $this->MessorLib->checkServersOnline($server, 'status');
            if ($response[0] == "online") {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(array('status' => 'Online'));
            } else {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(array('status' => 'Offline'));
            }
        }
    }


    public function verify()
    {
        $verify = $this->MessorLib->verify();
        $toServer = $this->MessorLib->toServer();
        $type = $this->request->post['type'];
        if ($type == "email_confirm" || $type == "sms_confirm") {
            $code = $this->request->post['code'];
            $response = $toServer->verify($verify->$type($code));
        } else {
            $response = $toServer->verify($verify->$type());
        }
        $text = $response->getResponseData('message');
        $text = str_replace("\n", "<br>", $text);
        $status = $response->getResponseData('status');
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array('status' => $status, 'text' => $text));
    }

    public function Messor()
    {
        $this->document->setTitle("Messor security dashboard");
        $this->load->language('extension/module/messor');
        if (!$this->MessorLib->isConfig()) {
            $this->registerPage();
            return;
        }

        $data['new_version'] = $this->MessorLib->newVersion();

        if (isset($this->request->post['reload']) && $this->request->post['reload'] == 'peerInfo') {
            $this->MessorLib->getAboutPeerOfServer();
        }

        $this->load->model('extension/module/messor');
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['block_ip'])) {
            $this->model_extension_module_messor->SaveSettings();
            $this->MessorLib->SaveSetting($this->request->post);
            $this->session->data['success'] = 'Настройки сохранены';
        }

        $list = $this->MessorLib->listSynchronization(true);
        $list = $this->MessorLib->decryptArray($list, array(2, 3, 6));
        if (!empty($list[0][0])) {
            $data['list_sync'] = $this->MessorLib->addID($list);
        } else {
            $data['list_sync'] = array();
        }
        $data['servers'] = $this->MessorLib->getServers();
        foreach ($data['servers'] as $key => $server) {
            $country = explode(',' , $server[1]);
            $data['servers'][$key][] = $country[0];
            $data['servers'][$key][1] = $country[1];
            $host = parse_url($data['servers'][$key][0]);
            $data['servers'][$key][3] = $host['host'];
        }
        if($this->MessorLib->isDatabase()) {
            $data['version_bd'] = $this->MessorLib->versionDatabase();
        } else {
            $this->SynchronizationNoJS();
            $data['version_bd'] = $this->MessorLib->versionDatabase();
        }
        $data['user_token'] = $this->session->data['user_token'];
        $data['path'] = $this->MessorLib->getPath();
        $data['path_list'] = $this->MessorLib->getPathList();
        $data['rules'] = $this->MessorLib->getRules();
        $data['settings'] = $this->MessorLib->getSetting();
        $data['error_log'] = $this->MessorLib->getErrorLog();
        $data['peer_log'] = $this->MessorLib->getPeerLog();
        $data['peer_info'] = $this->MessorLib->getAboutPeer();
        $data['last_sync'] = $this->MessorLib->lastSync();
        $data['list_archive'] = $this->MessorLib->listArchive();
        $data['peer_list'] = $this->MessorLib->getPeerList();
        $data['database_ip'] = $this->MessorLib->getDatabaseIP();
        $data['primary_server'] = $this->MessorLib->getPrimaryServer();
        $data['servers_hash'] = $this->MessorLib->getHashServerFile();
        $data['ip_white_list'] = $this->MessorLib->getListIP('white');
        $data['ip_detect_list'] = $this->MessorLib->getListIP('detect');
        $data['file_clean'] = $this->MessorLib->checkFileSize();
        $data['config'] = $this->MessorLib->getConfig();
        $data['download_file'] = $this->url->link('extension/module/messor/fileDownload', 'user_token=' . $this->session->data['user_token']);
        $data['ip_black'] = $this->url->link('extension/module/messor/ipBlackWhiteList', 'user_token=' . $this->session->data['user_token'] . '&type=black', true);
        $data['ip_white'] = $this->url->link('extension/module/messor/ipBlackWhiteList', 'user_token=' . $this->session->data['user_token'] . '&type=white', true);
        $data['sync_table'] = $this->url->link('extension/module/messor/synchronizationTable', 'user_token=' . $this->session->data['user_token'] . '&type=white', true);
        $data['archive_table'] = $this->url->link('extension/module/messor/archiveTable', 'user_token=' . $this->session->data['user_token'] . '&type=white', true);

        $this->document->addStyle('view/stylesheet/messor/main.min.css');
        $this->document->addScript('view/javascript/messor/main.min.js', 'footer');
        $data['scripts'] = $this->document->getScripts('footer');

        if (isset($this->request->post['reload'])) {
            echo $this->response->setOutput($this->load->view('extension/module/messor/main/index', $data));
        } else {
            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');
            $this->response->setOutput($this->load->view('extension/module/messor/main/index', $data));
        }
    }

    public function MessorAJAX()
    {
        if (isset($this->request->post['id_sync'])) {
            if ($this->MessorLib->deleteSyncItem($this->request->post['id_sync'])) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(array('status' => 'OK', 'text' => 'File was delete'));
            }
        }

        if (isset($this->request->post['id_archive_modal'])) {
            if ($this->MessorLib->deleteArchiveItemModal($this->request->post['id_archive_modal'])) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(array('status' => 'OK', 'text' => 'File was delete'));
            }
        }

        if (isset($this->request->post['id_sync_modal'])) {
            if ($this->MessorLib->deleteSyncItemModal($this->request->post['id_sync_modal'])) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(array('status' => 'OK', 'text' => 'File was delete'));
            }
        }

        if (isset($this->request->post['file_clean'])) {
            if ($this->MessorLib->clearFile($this->request->post['file_clean']) == 0) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(array('status' => 'OK', 'text' => 'File was delete'));
            }
        }

        if (isset($this->request->post['file_see'])) {
            $text = $this->MessorLib->viewFile($this->request->post['file_see']);
            if ($text) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(array('status' => 'OK', 'text' => $text));
            } else {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(array('status' => 'OK', 'text' => $text));
            }
        }
    }

    public function archiveTable()
    {
        // $data += $this->load->language('extension/module/messor');
				// $data += $this->GetBreadCrumbs();
		$this->document->addStyle('view/stylesheet/messor/main.min.css');
        $this->document->addScript('view/javascript/messor/main.min.js');
        $url = '';

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'ip_attack';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['sort_direction'])) {
            $sortDirection = $this->request->get['sort_direction'];
        } else {
            $sortDirection = 'DESC';
        }

        if ($sortDirection == 'ASC') {
            $url .= '&sort_direction=DESC';
        } else {
            $url .= '&sort_direction=ASC';
        }

        if (isset($this->request->get['sort_direction'])) {
            $sortDirectionPagination = $this->request->get['sort_direction'];
        } else {
            $sortDirectionPagination = 'DESC';
        }

        $data['user_token'] = $this->session->data['user_token'];
        $listIP = $this->MessorLib->listArchive(true);
        $listIP = $this->MessorLib->decryptArray($listIP, array(2, 3, 5));
        if (!empty($listIP[0][0])) {
            $listIP = $this->MessorLib->addIDModal($listIP);
        } else {
            $listIP = array();
        }
        $data['list_attack'] = $this->MessorLib->sortAttack($sort, $listIP, $sortDirection);
		foreach($data['list_attack'] as $item) {
			$data['ip_list'][] = $item[0];
		}
        list($data['list_attack'], $data['urls_pagination'], $data['num_page'], $data['prev'], $data['next']) = $this->MessorLib->pagination(count($data['ip_list']), 10, $page, $data['list_attack'],  $this->url->link('extension/module/messor/archiveTable', 'user_token=' . $this->session->data['user_token'] . '&sort_direction='. $sortDirection . '&sort='.$sort .  '&page={page}', true));
        $data['sort'] = $sort;
        $data['sort_direction'] = $sortDirection;

        $data['ip_attack'] = $this->url->link('extension/module/messor/archiveTable', 'user_token=' . $this->session->data['user_token'] . '&sort=ip_attack' . $url . '&page=' . $page, true);
        $data['time_attack'] = $this->url->link('extension/module/messor/archiveTable', 'user_token=' . $this->session->data['user_token'] . '&sort=time_attack' . $url . '&page=' . $page, true);
        $data['url_attack'] = $this->url->link('extension/module/messor/archiveTable', 'user_token=' . $this->session->data['user_token'] . '&sort=url_attack' . $url . '&page=' . $page, true);
        $data['user_agent'] = $this->url->link('extension/module/messor/archiveTable', 'user_token=' . $this->session->data['user_token'] . '&sort=user_agent' . $url . '&page=' . $page, true);
        $data['type_attack'] = $this->url->link('extension/module/messor/archiveTable', 'user_token=' . $this->session->data['user_token'] . '&sort=type_attack' . $url . '&page=' . $page, true);
        $data['post'] = $this->url->link('extension/module/messor/archiveTable', 'user_token=' . $this->session->data['user_token'] . '&sort=post' . $url . '&page=' . $page, true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/messor/archive', $data));
    }

    public function synchronizationTable()
    {
        $this->document->addStyle('view/stylesheet/messor/main.min.css');
        $this->document->addScript('view/javascript/messor/main.min.js');
        // $data += $this->load->language('extension/module/messor');
        // $data += $this->GetBreadCrumbs();
        $url = '';

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'ip_attack';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['sort_direction'])) {
            $sortDirection = $this->request->get['sort_direction'];
        } else {
            $sortDirection = 'DESC';
        }

        if ($sortDirection == 'ASC') {
            $url .= '&sort_direction=DESC';
        } else {
            $url .= '&sort_direction=ASC';
        }

        if (isset($this->request->get['sort_direction'])) {
            $sortDirectionPagination = $this->request->get['sort_direction'];
        } else {
            $sortDirectionPagination = 'DESC';
        }
        $data['user_token'] = $this->session->data['user_token'];
        $listIP = $this->MessorLib->listSynchronization(true);
        $listIP = $this->MessorLib->decryptArray($listIP, array(2, 3, 5));
        if (!empty($listIP[0][0])) {
            $listIP = $this->MessorLib->addIDModal($listIP);
        } else {
            $listIP = array();
        }
        $data['list_attack'] = $this->MessorLib->sortAttack($sort, $listIP, $sortDirection);
		foreach($data['list_attack'] as $item) {
			$data['ip_list'][] = $item[0];
		}
        if (isset($data['ip_list'])) {
            $count = count($data['ip_list']);
        } else {
            $count = 0;
        }
        list($data['list_attack'], $data['urls_pagination'], $data['num_page'], $data['prev'], $data['next']) = $this->MessorLib->pagination($count, 10, $page, $data['list_attack'],  $this->url->link('extension/module/messor/synchronizationTable', 'user_token=' . $this->session->data['user_token'] . '&sort_direction='. $sortDirection . '&sort='.$sort .  '&page={page}', true));
        $data['sort'] = $sort;
        $data['sort_direction'] = $sortDirection;

        $data['ip_attack'] = $this->url->link('extension/module/messor/synchronizationTable', 'user_token=' . $this->session->data['user_token'] . '&sort=ip_attack' . $url . '&page=' . $page, true);
        $data['time_attack'] = $this->url->link('extension/module/messor/synchronizationTable', 'user_token=' . $this->session->data['user_token'] . '&sort=time_attack' . $url . '&page=' . $page, true);
        $data['url_attack'] = $this->url->link('extension/module/messor/synchronizationTable', 'user_token=' . $this->session->data['user_token'] . '&sort=url_attack' . $url . '&page=' . $page, true);
        $data['user_agent'] = $this->url->link('extension/module/messor/synchronizationTable', 'user_token=' . $this->session->data['user_token'] . '&sort=user_agent' . $url . '&page=' . $page, true);
        $data['type_attack'] = $this->url->link('extension/module/messor/synchronizationTable', 'user_token=' . $this->session->data['user_token'] . '&sort=type_attack' . $url . '&page=' . $page, true);
        $data['post'] = $this->url->link('extension/module/messor/synchronizationTable', 'user_token=' . $this->session->data['user_token'] . '&sort=post' . $url . '&page=' . $page, true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/messor/table_attack', $data));
    }

    public function ipBlackWhiteList()
    {

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'black';
        }

        $data['type'] = $type;
        $data['count_add_ip'] = 3;

        if (isset($this->request->post['ip1'])) {
            $list = array();
            for ($i = 1; $i < $data['count_add_ip']; $i++) {
                switch ($this->request->post['time' . $i]) {
                    case "day": {
                            $this->request->post['time' . $i] = 1;
                            break;
                        }
                    case "week": {
                            $this->request->post['time' . $i] = 7;
                            break;
                        }
                    case "month": {
                            $this->request->post['time' . $i] = 31;
                            break;
                        }
                }
                $list[$this->request->post['ip' . $i]] = $this->request->post['time' . $i];
                if (!$this->request->post['ip' . $i]) {
                    unset($list[$this->request->post['ip' . $i]]);
                }
            }
            $this->MessorLib->addIP($type, $list);
        }

        if (isset($this->request->post['ip'])) {
            $listIp = $this->MessorLib->getListIP($type);
            $this->MessorLib->deleteIP($type, $listIp, $this->request->post['ip']);
        }

        $data['user_token'] = $this->session->data['user_token'];
        $data['ip_list'] = $this->MessorLib->getListIP($type);

        list($data['ip_list'], $data['urls_pagination'], $data['num_page'], $data['prev'], $data['next']) = $this->MessorLib->pagination(count($data['ip_list']), 7, $page, $data['ip_list'],  $this->url->link('extension/module/messor/ipBlackWhiteList', 'user_token=' . $this->session->data['user_token']  . '&type=' . $type .  '&page={page}', true));
        $data['time'] = array('day', 'week', 'month', 'forever');

        if (isset($this->request->post['reload'])) {
            echo $this->response->setOutput($this->load->view('extension/module/messor/table_blackwhite_list', $data));
        } else {
            $this->document->addStyle('view/stylesheet/messor/main.min.css');
            $this->document->addScript('view/javascript/messor/main.min.js');
            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');
            $this->response->setOutput($this->load->view('extension/module/messor/table_blackwhite_list', $data));
        }
    }

    public function fileDownload()
    {
        if (isset($this->request->get['file'])) {
            $file = BASE_PATH . $this->request->get['file'];
        }
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

    public function validationIp()
    {
        if (isset($this->request->post['ip'])) {
            if (($this->request->post['ip']) == "") {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(array('status' => 'OK'));
                return;
            }
            $response = $this->MessorLib->checkIP($this->request->post['ip']);
            if (!$response) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(array('status' => 'ERROR'));
            } else {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(array('status' => 'OK'));
            }
        }
    }

    public function logs()
    {
        $data['logs'] = $this->MessorLib->getLogs();
        $data['clean'] = $this->url->link('extension/module/messor/cleanFile', 'user_token=' . $this->session->data['user_token'], true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/messor/logs', $data));
    }

    public function cleanFile()
    {
        if (isset($this->request->get['file'])) {
            $this->MessorLib->fileClear($this->request->get['file']);
        }
        if (isset($this->request->get['redirect'])) {
            $this->response->redirect($this->url->link('extension/module/messor/Logs', 'user_token=' . $this->session->data['user_token'], true));
        } else {
            $this->response->redirect($this->url->link('extension/module/messor/Messor', 'user_token=' . $this->session->data['user_token'], true));
        }
    }
}

trait Registration
{
    /* регистрация */

    public function registerPage()
    {
        $this->load->model('user/user');
        $user_info = $this->model_user_user->getUser($this->user->getId());
        $data['name'] = $user_info['firstname'] . $user_info['lastname'];
        $match = preg_match('/^[a-zA-Z0-9\ ]{3,35}$/', $data['name']);
        $data['name'] = $match !== 1 ? '' : $data['name'];
        $data['email'] = $user_info['email'];
        $data['http_host'] = $this->request->server['HTTP_HOST'];

        $this->document->addStyle('view/stylesheet/messor/main.min.css');
        $this->document->addScript('view/javascript/messor/main.min.js');

        if (function_exists('openssl_encrypt') or function_exists('mcrypt_encrypt')) {
            $data['algoritm_ssl'] = "ok";
        }

        if (!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS'])) {
            $data['protocol'] = "https://";
        } else {
            $data['protocol'] = "http://";
        }

        $data['redirect'] = $this->url->link('extension/module/messor/Messor', 'user_token=' . $this->session->data['user_token'], true);
        $data['version_plugin'] = $this->MessorLib->versionPlugin();
        $data['op_version'] = VERSION;
        $data['admin_login'] = $this->MessorLib->generateLogin();
        $data['admin_password'] = $this->MessorLib->generatePassword();
        $data['network_password'] = $this->MessorLib->generatePassword();
        $data['country'] = $this->MessorLib->getCountryList();
        $data['host'] = $this->request->server['HTTP_HOST'];
        $data['servers'] = $this->MessorLib->getServers();
        $data['servers'] = $this->MessorLib->checkServersOnline($data['servers'], 2);
        $data['user_token'] = $this->session->data['user_token'];
        $data['success'] = $this->url->link('extension/module/messor/Messor', 'user_token=' . $this->session->data['user_token']);
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/messor/install/register', $data));
        return;
    }

    public function ajaxRegister()
    {
        $this->MessorLib->register($this->request->post['data']);
    }
    /* end registaration */
}

trait OpencartSystem
/* добавление модуля в меню и breadcrumbs */
{
    public function addLeftColumn(&$route, &$data)
    {
        $this->load->language('extension/module/messor');

        $messor = array();

        if ($this->user->hasPermission('access', 'extension/module/messor')) {
            $messor[] = array(
                'name'     => 'Messor',
                'href'     => $this->url->link('extension/module/messor/Messor', 'user_token=' . $this->session->data['user_token'], true),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'extension/module/messor')) {
            $messor[] = array(
                'name'     => 'Malware Cleaner',
                'href'     => $this->url->link('extension/module/messor/MCLMain', 'user_token=' . $this->session->data['user_token'], true),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'extension/module/messor')) {
            $messor[] = array(
                'name'     => 'File System Controll',
                'href'     => $this->url->link('extension/module/messor/FSCMain', 'user_token=' . $this->session->data['user_token'], true),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'extension/module/messor')) {
            $messor[] = array(
                'name'     => 'File Database Backup',
                'href'     => $this->url->link('extension/module/messor/FDBBMain', 'user_token=' . $this->session->data['user_token'], true),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'extension/module/messor')) {
            $messor[] = array(
                'name'     => 'File System Check',
                'href'     => $this->url->link('extension/module/messor/FSCheckMain', 'user_token=' . $this->session->data['user_token'], true),
                'children' => array()
            );
        }
        if ($this->user->hasPermission('access', 'extension/module/messor')) {
            $messor[] = array(
                'name'     => 'Security Settings',
                'href'     => $this->url->link('extension/module/messor/SecuritySettingsMain', 'user_token=' . $this->session->data['user_token'], true),
                'children' => array()
            );
        }


        $data['menus'][] = array(
            'id'       => 'menu-security',
            'icon'	   => 'fa-shield',
            'name'     => $this->language->get('name_left_column'),
            'href'     => '',
            'children' => $messor
        );
    }

    private function GetBreadCrumbs()
    {
        $data = array();
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/example', 'user_token=' . $this->session->data['user_token'], true)
        );
        return $data;
    }
    /* end breadcrumbs and add left column */

    /* Хуки, записывают в базу настройки */
    public function install()
    {
        $this->load->model('extension/module/messor');
        if ($this->validate()) {
            $settings['module_messor_status'] = 1;
            $this->load->model('setting/setting');
            $this->load->model('setting/extension');
            $this->load->model('user/user_group');
		    $this->model_setting_setting->editSetting('module_messor', $settings);

            $settings['module_messor_status'] = 1;
            $this->model_setting_extension->install('module', 'messor');


            $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/module/messor');
            $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/module/messor');

            // Call install method if it exsits
            // $this->load->controller('extension/messor/' . $this->request->get['extension'] . '/install');

            $this->session->data['success'] = $this->language->get('text_success');
            $this->model_extension_module_messor->createEvents();
        }

    }

    public function uninstall()
    {
        $this->load->model('extension/module/messor');
        if ($this->validate()) {
            $this->model_setting_extension->uninstall('messor', $this->request->get['extension']);

            // Call uninstall method if it exsits
            $this->load->controller('extension/module/messor' . $this->request->get['extension'] . '/uninstall');
            $this->model_extension_module_messor->deleteEvents();
            $this->session->data['success'] = $this->language->get('text_success');
        }
    
    }


    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/module/messor')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
    /* end hook */
}

trait MalwareClean
/* Модуль Malware Clean */
{
    public function MCLMain()
    {
        $this->document->setTitle("Messor Malware Сleaner ");
        $this->load->language('extension/module/messor');
        if (!$this->MessorLib->isConfig()) {
            $this->registerPage();
            return;
        }


        $this->document->addStyle('view/stylesheet/messor/main.min.css');
        $this->document->addScript('view/javascript/messor/main.min.js');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        if($this->free) {
            $data['link'] = "https://messor.network/upgrade/OpenCart/#MalwareCleaner";
            $this->response->setOutput($this->load->view('extension/module/messor/upgrade', $data));
            return;
        }

        $MCleaner = $this->MessorLib->MCleaner();
        $result = $this->MessorLib->MCleanerCheckVersion($MCleaner);
        if (!$result) {
            $result = $this->MessorLib->MCleanerUpdateVersion($MCleaner);
        }
        $data['config'] = $MCleaner->GetConfig();
        $data['result'] = $this->url->link('extension/module/messor/MCLResult', 'user_token=' . $this->session->data['user_token']);

        $this->response->setOutput($this->load->view('extension/module/messor/MCL/index', $data));
    }

    public function MCLResult()
    {
        $this->load->language('extension/module/messor');
        $this->document->setTitle("Messor Malware Сleaner ");
        $this->document->addStyle('view/stylesheet/messor/main.min.css');
        $this->document->addScript('view/javascript/messor/main.min.js');

        $MCleaner = $this->MessorLib->MCleaner();


        if (isset($this->request->get['delete'])) {
            $MCleaner->delete($this->request->get['delete']);
        }

        $MCleaner->SetConfig("PATH",  $this->request->post['PATH']);
        $MCleaner->SetConfig("EXTENSIONS_PHP", explode(" ", $this->request->post['EXTENSIONS_PHP']));
        $MCleaner->SetConfig("EXTENSIONS_CGI", explode(" ", $this->request->post['EXTENSIONS_CGI']));
        $MCleaner->SetConfig("EXTENSIONS_CGI", explode(" ", $this->request->post['EXTENSIONS_CGI']));
        $MCleaner->SetConfig("MAX_FILESIZE_MB",  (int)$this->request->post['MAX_FILESIZE_MB']);

        if (!empty($this->request->post['MAX_NEED_DETECTS'])) $MCleaner->SetConfig("MAX_NEED_DETECTS", (int)$this->request->post['MAX_NEED_DETECTS']);

        if (!empty($this->request->post['MAX_FILESIZE_PHP_ENABLE'])) $MCleaner->SetConfig("MAX_FILESIZE_PHP_ENABLE", true);
        else $MCleaner->SetConfig("MAX_FILESIZE_PHP_ENABLE", false);

        if (!empty($this->request->post['MAX_FILESIZE_CGI_ENABLE'])) $MCleaner->SetConfig("MAX_FILESIZE_CGI_ENABLE", true);
        else $MCleaner->SetConfig("MAX_FILESIZE_CGI_ENABLE", false);

        if (!empty($this->request->post['EXCLUDE_FILES'])) {
            $MCleaner->SetConfig("EXCLUDE_FILES", array_map('trim', explode("\n", $this->request->post['EXCLUDE_FILES'])));
        }

        if (!empty($this->request->post['EXCLUDE_FILES'])) {
            $MCleaner->SetConfig("EXCLUDE_FILES", array_map('trim', explode("\n", $this->request->post['EXCLUDE_FILES'])));
        }

        if (!empty($this->request->post['SIGNATURE_PHP'])) {
            $MCleaner->SetSignatures("detect_php_user", $this->request->post['SIGNATURE_PHP']);
        }

        if (!empty($this->request->post['SIGNATURE_CGI'])) {
            $MCleaner->SetSignatures("detect_cgi_user", $this->request->post['SIGNATURE_CGI']);
        }

        $start_time = microtime(1);
        $MCleaner->Scan();
        $work_time = round((microtime(1) - $start_time), 2);

        $data['user_token'] = $this->session->data['user_token'];
        $data['circle'] = $MCleaner->Percent();
        $data['work_time'] = $work_time;
        $data['result'] = $MCleaner->GetResult();
        $data['config'] = $MCleaner->GetConfig();
        $data['config']['signature_version'] = str_replace('Version','', $data['config']['signature_version']);
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/messor/MCL/result', $data));
    }

    public function deleteDangerFile()
    {
        $MCleaner = $this->MessorLib->MCleaner();

        if (isset($this->request->post['delete'])) {
            if ($MCleaner->delete($this->request->post['delete'])) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(array('status' => 'OK', 'text' => 'File was deleted'));
            } else {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(array('status' => 'ERROR', 'text' => 'File was NOT deleted'));
            }
        }
    }

    public function excludeDangerFile()
    {
        $MCleaner = $this->MessorLib->MCleaner();

        if (isset($this->request->post['exclude'])) {
            if ($MCleaner->exclude($this->request->post['exclude'])) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(array('status' => 'OK', 'text' => 'File was exclude'));
            } else {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(array('status' => 'ERROR', 'text' => 'File was NOT exclude'));
            }
        }
    }   /* end */
}

trait FileSystemControl
{
    public function FSCMain()
    {
        $this->document->setTitle("Messor File System Controll");
        $this->load->language('extension/module/messor');
        if (!$this->MessorLib->isConfig()) {
            $this->registerPage();
            return;
        }

        $this->document->addStyle('view/stylesheet/messor/main.min.css');
        $this->document->addScript('view/javascript/messor/main.min.js');

        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        if($this->free) {
            $data['link'] = "https://messor.network/upgrade/OpenCart/#FsControll";
            $this->response->setOutput($this->load->view('extension/module/messor/upgrade', $data));
            return;
        }

        $FSControll = $this->MessorLib->FSControll();
        $data['path'] = $FSControll->getDefaultPath();

        $data['result'] = $this->url->link('extension/module/messor/FSCResult', 'user_token=' . $this->session->data['user_token']);

        $this->response->setOutput($this->load->view('extension/module/messor/FSC/index', $data));
    }

    public function FSCResult()
    {
        $this->document->setTitle("Messor File System Controll");
        $this->load->language('extension/module/messor');
        $this->document->addStyle('view/stylesheet/messor/main.min.css');
        $this->document->addScript('view/javascript/messor/main.min.js');

        $FSControll = $this->MessorLib->FSControll();

        if (!empty($this->request->post['EXCLUDE_FILES'])) {
            $data['exclude_user'] = $FSControll->addExcludeType($this->request->post['EXCLUDE_FILES']);
        } else {
            $data['exclude_user'] = null;
        }

        if (isset($this->request->post['path'])) {
            $path = $this->request->post['path'];
            $FSControll->setPath($path);
        } else {
            $path = $FSControll->getDefaultPath();
        }

        $FSControll->setPath($path);

        $start_time = microtime(1);
        $FSControll->load_shot();
        $shot = $FSControll->getShot();
        if ($shot) {
            $view = 'extension/module/messor/FSC/result';
        } else {
            $view = 'extension/module/messor/FSC/one_time';
            $data['route'] = $this->url->link('extension/module/messor/FSCMain');
        }
        $FSControll->dirToArray($FSControll->getPath());
        $FSControll->Scan();
        $FSControll->write_shot();
        $work_time = round((microtime(1) - $start_time), 2);

        $data['path'] = $path;
        $data['result'] = $FSControll->getResult();
        if ($data['exclude_user'] != null) {
            $data['result']['exclude'] = array_merge($data['result']['exclude'], $data['exclude_user']);
        }
        $data['percent'] = $FSControll->Percent();
        $data['user_token'] = $this->session->data['user_token'];
        $data['work_time'] = $work_time;
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view($view, $data));
    }

    public function FSCexcludeFile()
    {
        $FSControll = $this->MessorLib->FSControll();
        if ($FSControll->addExclude($this->request->post['exclude'])) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array('status' => 'OK', 'text' => 'File was exclude'));
        }
    }

    public function FSCremoveFile()
    {
        $FSControll = $this->MessorLib->FSControll();
        if ($FSControll->removeExclude($this->request->post['remove'])) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array('status' => 'OK', 'text' => 'File was exclude'));
        }
    }
}

trait FileDatabaseBackup
{
    public function FDBBMain()
    {

        $this->document->setTitle('Messor BackUp');
        $this->load->language('extension/module/messor');
        if (!$this->MessorLib->isConfig()) {
            $this->registerPage();
            return;
        }

        $this->document->addStyle('view/stylesheet/messor/main.min.css');
        $this->document->addScript('view/javascript/messor/main.min.js');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        if($this->free) {
            $data['link'] = "https://messor.network/upgrade/OpenCart/#BackUp";
            $this->response->setOutput($this->load->view('extension/module/messor/upgrade', $data));
            return;
        }

        $FDBBackup = $this->MessorLib->FDBBackup();
        $data['filename'] = $FDBBackup->setDumpName();
        $FDBBackup::initDataBase(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $this->load->model('setting/setting');
        $config = $this->model_setting_setting->getSetting('config');
        $data['email'] = $config['config_email'];
        $data['avaliable_arch'] = $FDBBackup::avaliableArch();

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $FDBBackup::initDump($this->request->post['type_arch'], $this->request->post['filename']);
            if (isset($this->request->post['type_backup']) && $this->request->post['type_backup'] == "backup_db") {
                $FDBBackup::createDumpDB($this->request->post['tables']);
                switch ($this->request->post['action']) {
                    case 'download':
                        $FDBBackup::$dump->download($FDBBackup::$dump->filenameDumpDb);
                        $FDBBackup::$dump->remove($FDBBackup::$dump->filenameDumpDb);
                        break;
                    case 'email':
                        $Mailer = $FDBBackup->setMailer($FDBBackup::$dump->filenameDumpDb, $FDBBackup::$dump->dump_path, $this->request->post['email_user']);
                        $Mailer->send();
                        $FDBBackup::$dump->remove($FDBBackup::$dump->filenameDumpDb);
                        break;
                }
            }
            if (isset($this->request->post['type_backup']) && $this->request->post['type_backup'] == "backup_file") {
                if ($this->request->post['type_arch'] == ".zip") {
                    $FDBBackup::createDumpFileBackupZip($this->request->post['path']);
                } else {
                    $FDBBackup::createDumpFileBackupTar($this->request->post['path']);
                }

                switch ($this->request->post['action']) {
                    case 'download':
                        $FDBBackup::$dump->download($FDBBackup::$dump->filenameDumpFile);
                        $FDBBackup::$dump->remove($FDBBackup::$dump->filenameDumpDb);
                        break;
                    case 'email':
                        $Mailer = $FDBBackup->setMailer($FDBBackup::$dump->filenameDumpDb, $FDBBackup::$dump->dump_path,  $this->request->post['email_user']);
                        $Mailer->send();
                        $FDBBackup::$dump->remove($FDBBackup::$dump->filenameDumpDb);
                        break;
                }
            }
        }

        $data['path'] = $FDBBackup->getDefaultPath();
        $data['tables'] = $FDBBackup::$dbquery->getTables();
        $data['action'] = $this->url->link('extension/module/messor/FDBBMain', 'user_token=' . $this->session->data['user_token'], true);

        $this->response->setOutput($this->load->view('extension/module/messor/FDBB/index', $data));
    }
}

trait FileSystemCheck
{
    public function FSCheckMain()
    {
        $this->document->addStyle('view/stylesheet/messor/main.min.css');
        $this->document->addScript('view/javascript/messor/main.min.js');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->document->setTitle("Messor File System Check");
        $this->load->language('extension/module/messor');
        if (!$this->MessorLib->isConfig()) {
            $this->registerPage();
            return;
        }
        
        $FSCheck = $this->MessorLib->FSCheck();
        $data['path'] = $FSCheck->getDefaultPath();
        $data['action'] = $this->url->link('extension/module/messor/FSCheckResult', 'user_token=' . $this->session->data['user_token']);
        $this->response->setOutput($this->load->view('extension/module/messor/FSCheck/index', $data));
    }

    public function FSCheckResult()
    {
        $this->document->setTitle("Messor File System Check");
        $this->load->language('extension/module/messor');
        $this->document->addStyle('view/stylesheet/messor/main.min.css');
        $this->document->addScript('view/javascript/messor/main.min.js');

        $FSCheck = $this->MessorLib->FSCheck();
        if (isset($this->request->post['path'])) {
            $FSCheck->setPath($this->request->post['path']);
        } else {
            $FSCheck->setPath($FSCheck->getDefaultPath());
        }

        if (isset($this->request->post['path'])) {
            $exclude = explode('\n', $this->request->post['exclude']);
            $FSCheck->setExclude($exclude);
        }


        $data['result'] = $FSCheck->getResult();
        $data['statistic'] = $FSCheck->getCountStatistic();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/messor/FSCheck/result', $data));
    }
}

trait SecuritySettings
{
    public function SecuritySettingsMain()
    {
        $this->document->setTitle("Messor security settings");
        $this->load->language('extension/module/messor');
        if (!$this->MessorLib->isConfig()) {
            $this->registerPage();
            return;
        }

        $this->document->addStyle('view/stylesheet/messor/main.min.css');
        $this->document->addScript('view/javascript/messor/main.min.js');

        $SecuritySettings = $this->MessorLib->SecuritySettings();
        $this->load->model('user/user');
        $this->load->model('user/user_group');
        $this->load->model('setting/setting');
        $config = $this->model_setting_setting->getSetting('config');
        $users = $this->model_user_user->getUsers();
        $data['admin_panel'] = $SecuritySettings->checkAdminPanel();
        foreach ($users as $item) {
            $group = $this->model_user_user_group->getUserGroup($item['user_group_id']);
            if(preg_match('/admin|administrator|manager|test|root|support/', $item['username'])) {
                $novalid = true;
            } else {
                $novalid = false;
            }
            $data['user'][] = array(
                'group'     => $group['name'],
                'user_id'    => $item['user_id'],
                'login'      => $item['username'],
                'firstname'   => $item['firstname'],
                'lastname'   => $item['lastname'],
                'edit'       => $this->url->link('user/user/edit', 'user_token=' . $this->session->data['user_token'] . '&user_id=' . $item['user_id'], true),
                'novalid'   => $novalid
            );
        }
        $data['admin_login'] = $SecuritySettings->checkAdminLogin($data['user']);
        $data['last_version'] = $SecuritySettings->checkLastVersionOpencart(VERSION);
        $data['new_version'] = $SecuritySettings->getVersionOpencart();
        list($data['perms'], $data['result']) = $SecuritySettings->checkConfigPerms();
        $data['install_dir'] = $SecuritySettings->checkInstallDirectory();
        $data['prefix'] = $SecuritySettings->checkDBPrefix(DB_PREFIX);
        $data['show_error'] = $SecuritySettings->checkShowError($config['config_error_display']);
        $data['new_version_messor'] = $this->MessorLib->newVersion();
        if (DIR_STORAGE == DIR_SYSTEM . 'storage/' ||  DIR_STORAGE == null) {
            $data['storage'] = true;
        } elseif (!defined(DIR_STORAGE)) {
            $data['storage'] = false;
        }

        if (DB_USERNAME == 'root') {
            $data['mysql_root'] = true;
        } else {
            $data['mysql_root'] = false;
        }

        $count = array($data['last_version'], $data['admin_panel'], $data['admin_login'], $data['result'], $data['install_dir']);
        $data['count'] = 0;
        foreach ($count as $item) {
            if (!$item) {
                $data['count']++;
            }
        }
        if (!($data['prefix'] && $data['show_error'])) {
            $data['count']++;
        }

        if ($data['count'] == 0) {
            $data['percent'] = 0;
        } else {
            $data['percent'] = 100 * ($data['count'] / 6);
        }
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/messor/SecuritySettings/index', $data));
    }
}
