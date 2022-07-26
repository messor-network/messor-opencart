<?php

namespace src;

use src\Request\toServer;
use src\Config\Path;
use src\Utils\File;
use src\Utils\Random;
use src\Utils\Parser;
use src\Request\HttpRequest;

class Register
{
    public $http;

    public function __construct()
    {
        $this->http = new HttpRequest();
    }
    public function getIP()
    {
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function getRouteRequesToPeer()
    {
        $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . "/index.php?route=requestToPeer";
        return $url;
    }

    public function register()
    {
        $toServer = new toServer(null);
        $_SERVER['REMOTE_ADDR'] = $this->getIP();
        $data = [
            'user_email' => "developer@wwood.io",
            'user_password' => "shumaher777{}",
            'data' => null,
            'name' => "Fedya",
            'phone' => "",
            'email' => "developer@wwood.io",
            'company' => "",
            'country' => "RU",
            'lang' => "ru",
            'about' => "",
            'server_install_url' => "https://nl.messor.io/messor/",
            'url' => $this->getRouteRequesToPeer(),
            'encryption_alg' => "aes128",
            'reg_network_password' => Random::Rand(4, 5, 'luds'),
            'random_data' => "",
            'admin_login' => null,
            'admin_password' => null,
            'cms' => "opencart",
            'cms_version' => "3.0.3.8",
            'plugin_version' => "1.0.2"
        ];

        foreach ($data as $k => $v) {
            $this->http->setPost($k, $v);
        }
        $this->http->setPost('data', null);
        $server = $this->http->post('server_install_url');
        $toServer->request->setServer($server);
        $response = $toServer->register();
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
        }
    }
}
