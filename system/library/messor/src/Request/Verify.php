<?php

namespace src\Request;

/**
 * Класс получения типа верификации, запрошенного пользователем,
 * используется для повышения trust peer
 * @see toServer::verify()
 */
class Verify 
{
    /**
     * email верификация, запрос кода подтверждения
     *
     * @return array
     */
    public function email() 
    {
        return $data = array('type' => 'email');
    }

    /**
     * Подтверждение email отправкой кода
     *
     * @param string $data
     * @return array
     */
    public function email_confirm($data) 
    {
        return $data = array('type' => 'email_confirm', 'value' => $data);
    }

    /**
     * sms верификация, запрос кода подтверждения
     *
     * @return array
     */
    public function sms() 
    {
        return $data = array('type' => 'sms');
    }

    /**
     * Подтверждение sms отправкой кода
     *
     * @param sting $data
     * @return array
     */
    public function sms_confirm($data) 
    {
        return $data = array('type' => 'sms_confirm', 'value' => $data);
    }

    /**
     * phone верификация, запрос звонка на телефон
     *
     * @return array
     */
    public function phone() 
    {
        return $data = array('type' => 'phone');
    }

    /**
     * Подтверждение кода из телефонного вызова
     *
     * @param string $data
     * @return array
     */
    public function phone_confirm($data) 
    {
        return $data = array('type' => 'phone_confirm', 'value' => $data);
    }

    /**
     * DNS верификация, запрос кода подтверждения
     *
     * @return array
     */
    public function dns() 
    {
        return $data = array('type' => 'dns');
    }

    /**
     * Запрос проверки подтверждения DNS
     *
     * @return void
     */
    public function dns_confirm() 
    {
        return $data = array('type' => 'dns_confirm');
    }
    
    public function mail() 
    {
        return $data = array('type' => 'mail');
    }

    public function mail_confirm($data) 
    {
        return $data = array('type' => 'mail_confirm', 'value' => $data);
    }

    public function docs_confirm($data) 
    {
        return $data = array('type' => 'docs_confirm', 'value' => $data);
    }
}