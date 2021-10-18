<?php

namespace src\Request;

use src\Crypt\CryptPlain;
use src\Response\Response;
use src\Config\Path;
use src\Config\User;
use src\Request\Request;

class toPeer {

    public  $request;
    private $response;
    private $cryptPlain;

    public function __construct($server)
    {
        $this->request = new Request($server);
        $this->cryptPlain = new CryptPlain();
    }

    /**
     * Проверка доступности пира
     *
     * @return Response
     */
    public function ping() 
    {
       $header = array(
            'action'           => 'peer_ping',
            'client_version'   => Path::VERSION,
            'network_id'       => USER::$networkID,
        );
       $this->request->setHeader($header);
       $this->request->setCrypt($this->cryptPlain);
       $this->request->formatForSend();
       $this->response = $this->request->send();
       return new Response($this->response, $this->request->getCrypt());
    }

    /**
     * Получение списка серверов
     *
     * @return Response
     */
    public function getServerList()
    {
        $header = array(
            'action'           => 'peer_get_server_list',
            'client_version'   => Path::VERSION,
            'network_id'       => USER::$networkID,
        );
       $this->request->setHeader($header);
       $this->request->setCrypt($this->cryptPlain);
       $this->request->formatForSend();
       $this->response = $this->request->send();
       return new Response($this->response, $this->request->getCrypt());
    }

    /**
     * Получения списка пиров
     *
     * @return Response
     */
    public function getPeerList()
    {
        $header = array(
            'action'           => 'peer_get_peer_list',
            'client_version'   => Path::VERSION,
            'network_id'       => USER::$networkID,
        );
       $this->request->setHeader($header);
       $this->request->setCrypt($this->cryptPlain);
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
        );
        $data = array(
           'database_version' => $database,
        );
       $this->request->setHeader($header);
       $this->request->setData($data);
       $this->request->setCrypt($this->cryptPlain);
       $this->request->formatForSend();
       $this->response = $this->request->send();
       return new Response($this->response, $this->request->getCrypt());
    }
}