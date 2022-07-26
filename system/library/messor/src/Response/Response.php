<?php

namespace src\Response;

/**
 * Класс ответа от сервера
 */
class Response
{
    private $rawResponse;
    private $crypt;
    private $status;
    private $versionServer;
    private $responseRawData;
    private $responseData;

    /**
     * Инициализация
     *
     * @param string $rawResponse
     * @param \src\Crypt\iCrypt $crypt
     */
    public function __construct($rawResponse, \src\Crypt\iCrypt $crypt)
    {
        $this->crypt = $crypt;
        $this->parseResponse($rawResponse);
    }

    /**
     * Распарсивает строку ответа
     *
     * @param string $rawResponse
     * @return string
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

         if(isset($beginResponseData) && isset($endResponse)) {
            for($i=$beginResponseData+1; $i!=$endResponse; $i++) {
                $this->responseRawData .= $rawResponse[$i]."\n";
            }
            $this->responseData();
        }
    }

    /**
     * Устанавливает в переменную статус ошибку
     *
     * @return void
     */
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

    /**  
     * Устанавливает статус ответа
     * 
     * @param string $status
     * @return void
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
     * Получение распарсиваемых данных после *data*
     *
     * @param string|null $data
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
