<?php

namespace src\Request;

use src\Crypt\CryptPlain;
use src\Crypt\CryptRC4;
use src\Crypt\CryptOpenSSL;
use src\Response\Response;
use src\Config\Path;
use src\Config\User;
use src\Utils\File;
use src\Request\HttpRequest;

class toServer
{

    public $request;
    private $http;

    public function __construct($server)
    {
        $this->http = new HttpRequest();
        $this->request = new Request($server);
        $this->cryptPlain = new CryptPlain();
        if (USER::$encryptionAlg == "RC4") {
            $this->cryptEncrypt = new CryptRC4();
        } else {
            $this->cryptEncrypt = new CryptOpenSSL();
        }
    }
    /**
     * Получение статуса пира
     *
     * 
     * @return Response
     */
    public function status()
    {
        $header = array(
            'action'           => 'peer_status',
            'client_version'   => Path::VERSION,
            'network_id'       => USER::$networkID,
            'network_password' => USER::$networkPassword,
        );
        $data = array(
            'client_version' => Path::VERSION,
            'database_version' => File::read(Path::VERSION_BD),
            'server_list_version' => hash_file('sha256', Path::SERVERS),
        );
        $this->request->setHeader($header);
        $this->request->setData($data);
        $this->request->setCrypt($this->cryptEncrypt);
        $this->request->formatForSend();
        $this->response = $this->request->send();
        return new Response($this->response, $this->request->getCrypt());
    }
    /**
     * Синхронизация аттак с сервером
     *
     * @return Response
     */
    public function sync()
    {
        $header = array(
            'action'           => 'peer_sync',
            'client_version'   => Path::VERSION,
            'network_id'       => USER::$networkID,
            'network_password' => USER::$networkPassword,
        );
        $data = array(
            'client_version' => Path::VERSION,
            'server_list_version' => File::read(Path::SYNC_LIST),
        );
        $this->request->setHeader($header);
        $this->request->setData($data);
        $this->request->setCrypt($this->cryptEncrypt);
        $this->request->formatForSend();
        $this->response = $this->request->send();
        return new Response($this->response, $this->request->getCrypt());
    }

    /**
     * Посылка сообщения на сервер, для дебага
     *
     * @param [string] $message
     * @return Response
     */
    public function echo($message)
    {
        $header = array(
            'action'           => 'peer_echo',
            'client_version'   => Path::VERSION,
            'network_id'       => USER::$networkID,
            'network_password' => USER::$networkPassword,
        );
        $data = array(
            'message' => $message,
        );
        $this->request->setHeader($header);
        $this->request->setData($data);
        $this->request->setCrypt($this->cryptEncrypt);
        $this->request->formatForSend();
        $this->response = $this->request->send();
        return new Response($this->response, $this->request->getCrypt());
    }

    /**
     * Регистрация пира на сервере
     *
     * @return Response
     */
    public function register()
    {
        $post = $this->http->post();
        $server = $this->http->server();
        $header = array(
            'action'           => 'peer_register',
            'client_version'   => Path::VERSION,
        );
        $data = array(
            'user_email'     => $post['user_email'],
            'user_password'  => $post['user_password'],
            'version'        => Path::VERSION,
            'login'          => $post['admin_login'],
            'password'       => $post['admin_password'],
            'reg_network_password' => $post['reg_network_password'],
            'domain'         => $server['HTTP_HOST'],
            'ip'             => $server['SERVER_ADDR'],
            'url'            => $post['url'],
            'name'           => $post['name'],
            'company'        => $post['company'],
            'email'          => $post['email'],
            'phone'          => $post['phone'],
            'about'          => $post['about'],
            'country'        => $post['country'],
            'lang'           => $post['lang'],
            'encryption_alg' => $post['encryption_alg'],
            // 'encryption_key' => $post['encryption_key'],
            'client_version' => Path::VERSION,
            'os'             => $server['SERVER_SIGNATURE'] ? trim($server['SERVER_SIGNATURE']) : '', // todo check os
            'web_server'     => $server['SERVER_SOFTWARE'],
            'php_version'    => phpversion(),
            'cms'            => $post['cms'], // Wordpress OpenCart Magento etc...
            'cms_version'    => $post['cms_version'], // Wordpress 3.4.3
            'random_data'    => $post['random_data'],
            'plugin_version' => $post['plugin_version'],
        );
        $this->request->setHeader($header);
        $this->request->setData($data);
        $this->request->setCrypt($this->cryptPlain);
        $this->request->formatForSend();
        $this->response = $this->request->send();
        return new Response($this->response, $this->request->getCrypt());
    }
    /**
     * Верификация пира на сервере
     *
     * @param [string] $data
     * @return Response
     */
    public function verify($data)
    {
        $header = array(
            'action'           => 'peer_verify',
            'client_version'   => Path::VERSION,
            'network_id'       => USER::$networkID,
            'network_password' => USER::$networkPassword,
        );
        $this->request->setHeader($header);
        $this->request->setData($data);
        $this->request->setCrypt($this->cryptEncrypt);
        $this->request->formatForSend();
        $this->response = $this->request->send();
        return new Response($this->response, $this->request->getCrypt());
    }

    /**
     * Изменение информации о пире на сервере
     *
     * @param [string] $key
     * @param [string] $value
     * @return Response
     */
    public function editData($key, $value)
    {
        $header = array(
            'action'           => 'peer_edit_data',
            'client_version'   => Path::VERSION,
            'network_id'       => USER::$networkID,
            'network_password' => USER::$networkPassword,
        );
        $data = array(
            'key' => $key,
            'value' => $value
        );
        $this->request->setHeader($header);
        $this->request->setData($data);
        $this->request->setCrypt($this->cryptPlain);
        $this->request->formatForSend();
        $this->response = $this->request->send();
        return new Response($this->response, $this->request->getCrypt());
    }

    /**
     * Получерние информации о пире
     *
     * @return Response
     */
    public function info()
    {
        $header = array(
            'action'           => 'peer_info',
            'client_version'   => Path::VERSION,
            'network_id'       => USER::$networkID,
            'network_password' => USER::$networkPassword,
        );
        $this->request->setHeader($header);
        $this->request->setCrypt($this->cryptEncrypt);
        $this->request->formatForSend();
        $this->response = $this->request->send();
        return new Response($this->response, $this->request->getCrypt());
    }

    /**
     * Обновление клиента пира
     *
     * @return Response
     */
    public function upgrade()
    {
        $header = array(
            'action'           => 'peer_upgrade',
            'client_version'   => Path::VERSION,
            'network_id'       => USER::$networkID,
            'network_password' => USER::$networkPassword,
        );
        $this->request->setHeader($header);
        $this->request->setCrypt($this->cryptEncrypt);
        $this->request->formatForSend();
        $this->response = $this->request->send();
        return new Response($this->response, $this->request->getCrypt());
    }

    /**
     * Сброс пароля пира
     *
     * @return Response
     */
    public function passwordReset()
    {
        $header = array(
            'action'           => 'peer_password_reset',
            'client_version'   => Path::VERSION,
        );
        $this->request->setHeader($header);
        $this->request->setCrypt($this->cryptPlain);
        $this->request->formatForSend();
        $this->response = $this->request->send();
        return new Response($this->response, $this->request->getCrypt());
    }

    /**
     * Получения серверного листа
     *
     * @return Response
     */
    public function getServerList()
    {
        $header = array(
            'action'           => 'peer_get_server_list',
            'client_version'   => Path::VERSION,
            'network_id'       => USER::$networkID,
            'network_password' => USER::$networkPassword,
        );
        $this->request->setHeader($header);
        $this->request->setCrypt($this->cryptEncrypt);
        $this->request->formatForSend();
        $this->response = $this->request->send();
        return new Response($this->response, $this->request->getCrypt());
    }

    /**
     * Получение листа с пирами
     *
     * @param [string] $database
     * @return Response
     */
    public function getPeerList($database)
    {
        $header = array(
            'action'           => 'peer_get_peer_list',
            'client_version'   => Path::VERSION,
            'network_id'       => USER::$networkID,
            'network_password' => USER::$networkPassword,
        );
        $data = array(
            'database_version' => $database,
        );
        $this->request->setHeader($header);
        $this->request->setData($data);
        $this->request->setCrypt($this->cryptEncrypt);
        $this->request->formatForSend();
        $this->response = $this->request->send();
        return new Response($this->response, $this->request->getCrypt());
    }

    /**
     * Получение базы ip адресов
     *
     * @param [string] $database
     * @return Response
     */
    public function getDatabase($database)
    {
        $header = array(
            'action'           => 'peer_download_database',
            'client_version'   => Path::VERSION,
            'network_id'       => USER::$networkID,
            'network_password' => USER::$networkPassword,
        );
        $data = array(
            'database_version' => $database,
        );
        $this->request->setHeader($header);
        $this->request->setData($data);
        $this->request->setCrypt($this->cryptEncrypt);
        $this->request->formatForSend();
        $this->response = $this->request->send();
        return new Response($this->response, $this->request->getCrypt());
    }

    /**
     * отправка ping на сервер
     *
     * 
     * @return Response
     */
    public function ping()
    {
        $header = array(
            'action'           => 'peer_ping',
            'client_version'   => Path::VERSION,
        );
        $this->request->setHeader($header);
        $this->request->setCrypt($this->cryptPlain);
        $this->request->formatForSend();
        $this->response = $this->request->send();
        return new Response($this->response, $this->request->getCrypt());
    }

    /**
     * авторизация на сервере
     *
     * 
     * @return Response
     */
    public function auth()
    {
        $post = $this->http->post();
        $header = array(
            'action'           => 'user_auth',
            'client_version'   => Path::VERSION,
        );
        $data = array(
            'user_email'          => $post['user_email'],
            'user_password'       => $post['user_password'],
        );
        $this->request->setHeader($header);
        $this->request->setData($data);
        $this->request->setCrypt($this->cryptPlain);
        $this->request->formatForSend();
        $this->response = $this->request->send();
        return new Response($this->response, $this->request->getCrypt());
    }

    public function peerSignVersion()
    {
        $header = array(
            'action'           => 'peer_sign_version',
            'client_version'   => Path::VERSION,
            'network_id'       => USER::$networkID,
            'network_password' => USER::$networkPassword,
        );
        $this->request->setHeader($header);
        $this->request->setCrypt($this->cryptEncrypt);
        $this->request->formatForSend();
        $this->response = $this->request->send();
        return new Response($this->response, $this->request->getCrypt());
    }

    public function peerSignVersionUpgrade()
    {
        $header = array(
            'action'           => 'peer_sign_upgrade',
            'client_version'   => Path::VERSION,
            'network_id'       => USER::$networkID,
            'network_password' => USER::$networkPassword,
        );
        $this->request->setHeader($header);
        $this->request->setCrypt($this->cryptEncrypt);
        $this->request->formatForSend();
        $this->response = $this->request->send();
        return new Response($this->response, $this->request->getCrypt());
    }

    public function peerChangeKey()
    {
        $header = array(
            'action'           => 'peer_change_key',
            'client_version'   => Path::VERSION,
            'network_id'       => USER::$networkID,
            'network_password' => USER::$networkPassword,
        );
        $this->request->setHeader($header);
        $this->request->setCrypt($this->cryptEncrypt);
        $this->request->formatForSend();
        $this->response = $this->request->send();
        return new Response($this->response, $this->request->getCrypt());
    }
}
