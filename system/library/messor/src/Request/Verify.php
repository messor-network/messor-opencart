<?php

namespace src\Request;

/**
 * The receiving class of the verification type requested by the user.
 * Used to increase trust peer
 * @see toServer::verify()
 */
class Verify 
{
    /**
     * email verification, verification code request
     *
     * @return array
     */
    public function email() 
    {
        return $data = array('type' => 'email');
    }

    /**
     * Email confirmation by sending a code
     *
     * @param string $data
     * @return array
     */
    public function email_confirm($data) 
    {
        return $data = array('type' => 'email_confirm', 'value' => $data);
    }

    /**
     * sms verification, confirmation code request
     *
     * @return array
     */
    public function sms() 
    {
        return $data = array('type' => 'sms');
    }

    /**
     * SMS confirmation by sending a code
     *
     * @param sting $data
     * @return array
     */
    public function sms_confirm($data) 
    {
        return $data = array('type' => 'sms_confirm', 'value' => $data);
    }

    /**
     * phone verification, phone call request
     *
     * @return array
     */
    public function phone() 
    {
        return $data = array('type' => 'phone');
    }

    /**
     * Code confirmation from a phone call
     *
     * @param string $data
     * @return array
     */
    public function phone_confirm($data) 
    {
        return $data = array('type' => 'phone_confirm', 'value' => $data);
    }

    /**
     * DNS verification, verification code request
     *
     * @return array
     */
    public function dns() 
    {
        return $data = array('type' => 'dns');
    }

    /**
     * DNS Validation Query
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