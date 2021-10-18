<?php 

namespace src\Crypt;

class CryptPlain implements iCrypt
{

    function __construct()
    {

    }

    public function Encrypt($data)
    {
        return base64_encode($data);
    }

    public function Decrypt($data)
    {
        return base64_decode($data);
    }
}