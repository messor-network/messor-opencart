<?php

namespace src\Request;

class Verify 
{
    public function email() 
    {
        return $data = array('type' => 'email');
    }

    public function email_confirm($data) 
    {
        return $data = array('type' => 'email_confirm', 'value' => $data);
    }

    public function sms() 
    {
        return $data = array('type' => 'sms');
    }

    public function sms_confirm($data) 
    {
        return $data = array('type' => 'sms_confirm', 'value' => $data);
    }

    public function phone() 
    {
        return $data = array('type' => 'phone');
    }

    public function phone_confirm($data) 
    {
        return $data = array('type' => 'phone_confirm', 'value' => $data);
    }

    public function dns() 
    {
        return $data = array('type' => 'dns');
    }

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