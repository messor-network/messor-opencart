<?php

namespace src\Request;

use src\Config\User;
use src\Config\Path;
use src\Utils\Parser;
use src\Crypt\iCrypt;

/**
 * Generates a request to the server to send data
 */
class Request
{
    private $server;
    private $data;
    private $header;
    private $crypt;
    private $type;
    private $request;

    /** @param string $server */
    public function __construct($server)
    {
        $this->server = $server;
        USER::init();
    }

    /**
     * Sending data using one of the methods
     *
     * @return mixed
     */
    public function send()
    {
        if (function_exists('curl_init')) {
            return $this->CurlRequest();
        } else {
            return $this->GetContentRequest();
        }
    }

    /**
     * Setting the data to send to the server *data*
     *
     * @param string $data
     * @return void
     */
    public function setData($data = '')
    {
        $this->data = Parser::toStringURL($data);
    }

    /**
     * Setting the header data to send to the server
     *
     * @param string $header
     * @return void
     */
    public function setHeader($header)
    {
        $this->header = Parser::toStringURL($header);
    }

    /**
     * Forming a data string to be sent to the server
     *
     * @return void
     */
    public function formatForSend()
    {
        $this->request = $this->header;
        if ($this->data) {
            $this->request .= '*data*' . "\n";
            $this->request .= $this->crypt->Encrypt($this->data);
        }
    }

    /**
     * Setting the server to which the data will be transferred
     *
     * @param string $server
     * @return void
     */
    public function setServer($server)
    {
        $this->server = $server;
    }

    /**
     * Sets the encryption type
     *
     * @param iCrypt $crypt
     * @return void
     */
    public function setCrypt(iCrypt $crypt)
    {
        $this->crypt = $crypt;
    }

    /**
     * Gets the encryption type
     *
     * @return iCrypt
     */
    public function getCrypt()
    {
        return $this->crypt;
    }

    /**
     * Sending with Curl
     *
     * @return string|bool
     */
    private function CurlRequest()
    {
        $ch = curl_init($this->server);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, USER::$userAgent);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->request);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($this->request)
        ));
        if ($content = curl_exec($ch)) {
            curl_close($ch);
            return $content;
        } else {
            return false;
        }
    }
    /**
     * Sending via file_get_contents
     *
     * @return string
     */
    private function GetContentRequest()
    {
        $opts = array(
            'http' =>
            array(
                'method'     => 'POST',
                'user_agent' => USER::$userAgent,
                'header'     => 'Content-type: application/x-www-form-urlencoded',
                'content'    => $this->request
            )
        );

        $context  = stream_context_create($opts);
        $content = file_get_contents($this->server, false, $context);
        return $content;
    }
}
