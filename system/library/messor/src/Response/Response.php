<?php

namespace src\Response;

class Response
{
    private $rawResponse;
    private $crypt;
    private $status;
    private $versionServer;
    private $responseRawData;
    private $responseData;

    public function __construct($rawResponse, $crypt)
    {
        $this->crypt = $crypt;
        $this->parseResponse($rawResponse);
    }

    /**
     * Распарсивает строку ответа
     *
     * @param [string] $rawResponse
     * @return void
     */
    private function parseResponse($rawResponse)
    {
        $rawResponse = explode("\n", $rawResponse);
        foreach ($rawResponse as $k => $line) {
            $line = trim($line);
            if ($line == '--- BEGIN MESSOR ---') {
                $beginResponse = ++$k;
            }
            if ($line == "*data*") {
                $beginResponseData = $k;
            }
            if ($line == '--- END MESSOR ---') {
                $endResponse = $k;
                break;
            }
        }
        if (!isset($beginResponse)) { 
            $this->errorParseResponse();
            return;
        }
        for($i=$beginResponse; $i!=$beginResponseData; $i++) {
            $tmp=explode('=', $rawResponse[$i]);
            $responseHeader[$tmp[0]]=$tmp[1];
        }
         $this->status = $responseHeader['status'];
         $this->versionServer = $responseHeader['server_version'];

         if(isset($beginResponseData)) {
            for($i=$beginResponseData+1; $i!=$endResponse; $i++) {
                $this->responseRawData .= $rawResponse[$i]."\n";
            }
            $this->responseData();
        }
    }

    private function errorParseResponse() 
    {
        $this->status = 'Error parse response';
    }

    /**
     * Получает статус ответа сервера
     *
     * @return string
     */
    public function getStatus() 
    {
        return $this->status;
    }

    /** Устанавливает статус ответа 
     * 
     * @param string $status
     * 
    */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Получает версию сервера
     *
     * @return string
     */
    public function getVersionServer() 
    {
        return $this->versionServer;
    }
    
    /**
     * Распарсирование данных в *data* пришедшие
     * с сервера
     *
     * @return void
     */
    private function responseData()
    {
        $this->responseData = $this->crypt->Decrypt($this->responseRawData);
        $data = explode("\n", $this->responseData);
        $this->responseData=array();
        if(is_array($data)) {
            foreach ($data as $line) {
                $line=explode("=", $line);
                if(!empty($line[0])) {
                    $this->responseData[$line[0]]=urldecode($line[1]);
                }
            }
        } else {
            $this->responseData='';
        }
    }

    /**
     * Получение распарсиных данных после *data*
     *
     * @param [string] $data
     * @return string|array
     */
    public function getResponseData($data=null)
    {
        if ($data != null){
            return $this->responseData[$data];
        } else {
            return $this->responseData;
        }
    }
}
