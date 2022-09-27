<?php

use main\Adapter;
use messor\cms\Opencart;
use messor\Autoloader;
use src\Config\Path;
use src\Utils\File;
use src\Request\HttpRequest;
use src\Request\Client\Client;

class ControllerExtensionModuleMessor extends Controller
{
    use Opencart;

    private $MessorLib;
    private $adapter;

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->library('messor/Autoloader');
        $this->adapter = new Adapter();
    }

    public function alertMessor()
    {
        if ($this->getRoute() == $this->pathToPeer) {
            return;
        }
        $setting = $this->adapter->MessorLib->getSetting();
        $systemSetting = $this->adapter->MessorLib->getSystemSetting();
        if ($this->adapter->MessorLib->checkUpdateDay($hour = 24, PATH::SETTINGS)) {
            $this->adapter->MessorLib->saveSetting($setting);
        }

        if ($systemSetting['cloudflare'] == 1) {
            $ip = $this->adapter->MessorLib->getIP(true);
        } else {
            $ip = $this->adapter->MessorLib->getIP();
        }

        if ($setting['lock'] == "js_unlock") {
            if (!$this->adapter->MessorLib->existHashIP($ip)) {
                $this->adapter->MessorLib->addHashIP($ip, $setting['js_salt']);
            }
        }

        if ($this->adapter->MessorLib->checkUpdateDay($hour = 24, PATH::DAY)) {
            $this->adapter->MessorLib->deleteAllHashIP();
            $this->adapter->MessorLib->deleteScoresDetect();
            $this->adapter->MessorLib->deleteScoresAllow();
            $this->adapter->MessorLib->updateClient();
            File::clear(PATH::DAY);
        }

        if (isset($this->request->get['status']) && $this->request->get['status'] == 'redirect') {
            return;
        }

        $route = $this->getUrlLink('hashJs', null, false);
        $url = $this->getUrl();
        static $flag = '';
        $resp = $this->getRoute();
        if (!(isset($resp) && $resp == "extension/module/messor/hashJs")) {
            if (!$flag) {
                $this->adapter->MessorLib->check($ip = null, $disableDetect = array('path'), $this->notFound(), $route, $url);
                $flag = true;
            }
        }
    }

    public function detect()
    {
        $setting = $this->adapter->MessorLib->getSetting();
        $systemSetting = $this->adapter->MessorLib->getSystemSetting();
        $http = new HttpRequest();

        
        if ($systemSetting['cloudflare'] == 1) {
            $ip = $this->adapter->MessorLib->getIP(true);
        } else  {
            $ip = $this->adapter->MessorLib->getIP(false);
        }

         if ($setting['lock'] == "js_unlock") {
            if (!$this->adapter->MessorLib->existHashIP($ip)) {
                $this->adapter->MessorLib->addHashIP($ip, $setting['js_salt']);
            }
        }

        if ($this->adapter->MessorLib->checkUpdateDay($hour = 26, PATH::DAY)) {
            $this->adapter->MessorLib->deleteAllHashIP();
            $this->adapter->MessorLib->deleteScoresDetect();
            $this->adapter->MessorLib->deleteScoresAllow();
            $this->adapter->MessorLib->updateClient();
            File::clear(PATH::DAY);
        }

        if (isset($this->request->get['status']) && $this->request->get['status'] == 'redirect') {
            return;
        }

        $route = $this->getUrlLink('hashJs', null, false);
        $url = $this->getUrl();

        if ($this->isImage()) return;
        
        static $flag = '';
        $resp = $this->getRoute();
        if (!(isset($resp) && $resp == "extension/module/messor/hashJs")) {
            if (!$flag) {
                $this->adapter->MessorLib->check($ip = null, $disableDetect = array(), $this->notFound(), $route, $url);
                $flag = true;
            }
        }
    }

    public function requestToPeer()
    {
        $key = $this->getRequestGet('key');
        $this->adapter->MessorLib->requestToPeer($key);
    }

    public function hashJs()
    {
        $route = $this->getRequestGet('url');
        if ($this->getRequestGet('key') !== null ) {
            $key = $this->getRequestGet('key');
        } else {
            die;
        }
        if ($this->adapter->MessorLib->hashJs($key)) {
            $this->redirectWithoutUrl($route);
        }
    }
}
