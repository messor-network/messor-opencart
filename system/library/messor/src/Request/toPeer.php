<?php

namespace src\Request;

use src\Crypt\CryptPlain;
use src\Response\Response;
use src\Config\Path;
use src\Config\User;
use src\Request\Request;

/**
 * Sending peer to peer requests
 */
class toPeer {

    /** @var Request */
    public  $request;
    /** @var HttpRequest */
    private $response;
    /** @var \src\Crypt\iCrypt */
    private $cryptPlain;

    /** @param string $server */
    public function __construct($server)
    {
        $this->request = new Request($server);
        $this->cryptPlain = new CryptPlain();
    }

    /**
     * Peer Availability Check
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
     * Getting a list of servers
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
     * Getting a list of peers
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
     * Getting a database of ip addresses
     *
     * @param string $database
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