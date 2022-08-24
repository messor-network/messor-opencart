<?php 

namespace src\Crypt;

/**
 * Class for forming a base64 string without encryption
 */
class CryptPlain implements iCrypt
{
    /** @see iCrypt::Encrypt() */
    public function Encrypt($data)
    {
        return base64_encode($data);
    }

    /** @see iCrypt::Decrypt() */
    public function Decrypt($data)
    {
        return base64_decode($data);
    }
}