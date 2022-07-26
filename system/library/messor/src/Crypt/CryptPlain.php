<?php 

namespace src\Crypt;

/**
 * base64 без шифрования
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