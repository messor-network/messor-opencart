<?php 

namespace src\Crypt;

use src\Config\User;
use src\Exception\CryptException;

/**
 *  Encryption class using OpenSSL library algorithms
 */
class CryptOpenSSL implements iCrypt
{
    /** @var string Encryption type */
    private $typeCrypt;

    /** @var string Encryption key */
    private $encryptionKey;

    function __construct()
    {
        $this->typeCrypt = User::$encryptionAlg;
        $this->encryptionKey = User::$encryptionKey;

    }

    /** @see iCrypt::Encrypt() */
    public function Encrypt($data)
    {
        $ivlen = openssl_cipher_iv_length($cipher=$this->getAlgCrypt());
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($data, $cipher, $this->encryptionKey, $options=OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $this->encryptionKey, $as_binary=true);
        return base64_encode( $iv.$hmac.$ciphertext_raw );
    }

    /** 
     * @see iCrypt::Decrypt()
     * @throws CryptException
     */
    public function Decrypt($data)
    {
        $data = base64_decode($data);
        $ivlen = openssl_cipher_iv_length($cipher=$this->getAlgCrypt());
        $iv = substr($data, 0, $ivlen);
        $hmac = substr($data, $ivlen, $sha2len=32);
        $ciphertext_raw = substr($data, $ivlen+$sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $this->encryptionKey, $options=OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $this->encryptionKey, $as_binary=true);
        try {
            if (hash_equals($hmac, $calcmac)) {
                return $original_plaintext;
            } else {
                throw new CryptException();
            }
        } catch (CryptException $e) {
            $e->DecryptError("decrypt error invalid data");
        }
    }

    /**
     * Getting Encryption Algorithms Available in OpenSSL
     * 
     * @return string
     */
    public function getAlgCrypt() 
    {
        switch($this->typeCrypt) {
        
            case "aes128": {
                return "AES-128-CBC";
            break;
            }
            case "aes256": {
                return "AES-256-CBC";
            break;
            }
            case "bf": {
                return "bf-cbc";
            break;
            }    
        }   
    }
}