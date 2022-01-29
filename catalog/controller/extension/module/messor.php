<?php

use messor\Autoloader;
use messor\MessorLib;
use src\Config\Path;
use src\Utils\Parser;
use src\Utils\File;
use src\Utils\Random;
use src\Request\HttpRequest;
use src\Request\Client\Client;

class ControllerExtensionModuleMessor extends Controller
{
    private $MessorLib;

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->library('messor/Autoloader');
        $this->load->library('messor/MessorLib');
        $this->MessorLib = new MessorLib($this->registry);
    }

    public function alertMessor()
    {
        if (isset($this->request->get['route'])) {
            if ($this->request->get['route'] == "extension/module/messor/requestToPeer") {
                return;
            }
        }
        $setting = Parser::toArraySetting(File::read(PATH::SETTINGS));
        $systemSetting = Parser::toArraySetting(File::read(PATH::SYSTEM_SETTINGS));
        if ($this->MessorLib->checkUpdateDay($hour = 24, PATH::SETTINGS)) {
            $setting['js_salt'] = Random::Rand(7, 12);
            $string = '';
            $string .= $setting['js_salt'];
            File::clear(PATH::SETTINGS);
            File::write(PATH::SETTINGS, Parser::toSettingArray($setting));
        }

        if ($setting['lock'] == "js_unlock") {
            $http = new HttpRequest();
            if ($systemSetting['cloudflare'] == 1) {
                $ip = $http->server('HTTP_CF_CONNECTING_IP');
                if (!$ip) {
                    $ip = $http->server('REMOTE_ADDR');
                }
            } else {
                $ip = $http->server('REMOTE_ADDR');
            }
            if (!file_exists(PATH::IPHASH . $ip)) {
                $string = '';
                $string .= $ip . $setting['js_salt'];
                $hash = hash('sha256', $string);
                File::write(PATH::IPHASH . $ip, $hash);
            }
        }

        if ($this->MessorLib->checkUpdateDay($hour = 26, PATH::DAY)) {
            $files = scandir(PATH::IPHASH);
            foreach ($files as $file) {
                if (!is_dir($file)) {
                    unlink(PATH::IPHASH . $file);
                }
            }
            $this->MessorLib->deleteScoresDetect();
            Client::init();
            Client::update();
            File::clear(PATH::DAY);
        }

        if (isset($this->request->get['status']) && $this->request->get['status'] == 'redirect') {
            return;
        }
        $url = $this->url->link('extension/module/messor/hashJs');
        if (isset($this->request->get['route'])) {
            $route = 'default_route=' . $this->request->get['route'];
        } else {
            $route = 'default_route=common/home';
        }
        if (isset($this->request->get['path'])) {
            $route .= '&path=' . $this->request->get['path'];
        }
        if (isset($this->request->get['product_id'])) {
            $route .= '&product_id=' . $this->request->get['product_id'];
        }
        static $flag = '';
        if (!(isset($this->request->get['route']) && $this->request->get['route'] == "extension/module/messor/hashJs")) {
            if (!$flag) {
                $this->MessorLib->check($ip = null, $disableDetect = array('path'), $route, $url);
                $flag = true;
            }
        }
    }

    public function detect()
    {
        $setting = Parser::toArraySetting(File::read(PATH::SETTINGS));
        $systemSetting = Parser::toArraySetting(File::read(PATH::SYSTEM_SETTINGS));
        $http = new HttpRequest();

        if ($systemSetting['cloudflare'] == 1) {
            $ip = $http->server('HTTP_CF_CONNECTING_IP');
            if (!$ip) {
                $ip = $http->server('REMOTE_ADDR');
            }
        } else {
            $ip = $http->server('REMOTE_ADDR');
        }
        if ($setting['lock'] == "js_unlock") {
            $http = new HttpRequest();
            if (!file_exists(PATH::IPHASH . $ip)) {
                $string = '';
                $string .= $ip . $setting['js_salt'];
                $hash = hash('sha256', $string);
                File::write(PATH::IPHASH . $ip, $hash);
            }
        }

        if ($this->MessorLib->checkUpdateDay($hour = 26, PATH::DAY)) {
            $files = scandir(PATH::IPHASH);
            foreach ($files as $file) {
                if (!is_dir($file)) {
                    unlink(PATH::IPHASH . $file);
                }
            }
            $this->MessorLib->deleteScoresDetect();
            Client::init();
            Client::update();
            File::clear(PATH::DAY);
        }

        if (isset($this->request->get['status']) && $this->request->get['status'] == 'redirect') {
            return;
        }
        $url = $this->url->link('extension/module/messor/hashJs');
        if (isset($this->request->get['route'])) {
            $route = 'default_route=' . $this->request->get['route'];
        } else {
            $route = 'default_route=common/home';
        }
        if (isset($this->request->get['path'])) {
            $route .= '&path=' . $this->request->get['path'];
        }
        if (isset($this->request->get['product_id'])) {
            $route .= '&product_id=' . $this->request->get['product_id'];
        }
        if (isset($this->request->get['_route_'])) {
            $check = pathinfo($this->request->get['_route_']);
            $list = array('svg', 'jpg', 'jpeg', 'png', 'webp', 'gif', 'woff', 'ttf', 'eot', 'woff2', 'css', 'js');
            if (isset($check['extension']) && in_array(strtolower($check['extension']), $list)) {
                return;
            }
        }
        if (isset($this->request->get['route'])) {
            $check = pathinfo($this->request->get['route']);
            $list = array('svg', 'jpg', 'jpeg', 'png', 'webp', 'gif', 'woff', 'ttf', 'eot', 'woff2', 'css', 'js');

            if (isset($check['extension']) && in_array(strtolower($check['extension']), $list)) {
                return;
            }
        }
        static $flag = '';
        if (!(isset($this->request->get['route']) && $this->request->get['route'] == "extension/module/messor/hashJs")) {
            if (!$flag) {
                $this->MessorLib->check($ip = null, $disableDetect = array(), $route, $url);
                $flag = true;
            }
        }
    }

    public function requestToPeer()
    {
        $key = $this->request->get['key'];
        $this->MessorLib->requestToPeer($key);
    }

    public function hashJs()
    {
        $route = $this->request->get['default_route'];
        if ($route == '') $route = "common/home";
        if (isset($this->request->get['path'])) {
            $route .= '&path=' . $this->request->get['path'];
        }
        if (isset($this->request->get['product_id'])) {
            $route .= '&product_id=' . $this->request->get['product_id'];
        }
        if (isset($this->request->get['key'])) {
            $key = $this->request->get['key'];
        } else {
            die;
        }
        if ($this->MessorLib->hashJs($key)) {
            $this->response->redirect($this->url->link($route));
        };
    }
}
