<?php

namespace src\Crypt;

/**
 * Interface for implementing encryption and decryption methods
 * 
 */
interface iCrypt 
{
    /**
     * Encryption of the transmitted string
     *
     * @param string $data
     * @return string
     */
    public function Encrypt($data);

    /**
     * Decrypts the given string
     *
     * @param string $data
     * @return string
     */
    public function Decrypt($data);
}