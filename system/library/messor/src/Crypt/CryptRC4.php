<?php 

namespace src\Crypt;

use src\Config\User;

class CryptRC4 implements iCrypt
{   
    private $typeCrypt;
    private $encryptionKey;

    function __construct()
    {
        $this->typeCrypt = User::$encryptionAlg;
        $this->encryptionKey = User::$encryptionKey;
    }

    public function Encrypt($data)
    {
        for ($i = 0; $i < 256; $i++) {
            $s[$i] = $i;
        }
        $j = 0;
        for ($i = 0; $i < 256; $i++) {
            $j = ($j + $s[$i] + ord(User::$encryptionKey[$i % strlen(User::$encryptionKey)])) % 256;
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
        }
        $i = 0;
        $j = 0;
        $res = '';
        for ($y = 0; $y < strlen($data); $y++) {
            $i = ($i + 1) % 256;
            $j = ($j + $s[$i]) % 256;
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
            $res .= $data[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
        }
        return base64_encode($res);
    }

    public function Decrypt($data)
    {
        for ($i = 0; $i < 256; $i++) {
            $s[$i] = $i;
        }
        $j = 0;
        for ($i = 0; $i < 256; $i++) {
            $j = ($j + $s[$i] + ord(User::$encryptionKey[$i % strlen(User::$encryptionKey)])) % 256;
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
        }
        $i = 0;
        $j = 0;
        $res = '';
        for ($y = 0; $y < strlen($data); $y++) {
            $i = ($i + 1) % 256;
            $j = ($j + $s[$i]) % 256;
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
            $res .= $data[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
        }
        return $res;
    }
}