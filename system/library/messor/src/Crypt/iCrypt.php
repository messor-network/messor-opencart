<?php

namespace src\Crypt;

/**
 * Интерфейс для реализации методов шифрования и дешифрования
 * 
 */
interface iCrypt 
{
    /**
     * Шифрование переданной строки
     *
     * @param string $data
     * @return string
     */
    public function Encrypt($data);

    /**
     * Дешифрует переданную строку
     *
     * @param string $data
     * @return string
     */
    public function Decrypt($data);
}