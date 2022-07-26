<?php

namespace src\Exception;

use src\Logger\Logger;
use src\Utils\File;
use src\Config\Path;

/**
 * Класс исключений шифрования
 */
class CryptException extends \Exception {

    /**
     * @param string $message
     * @param integer $code
     * @param \Exception|null $previous
     */
    public function __construct($message="", $code = 0, \Exception $previous = null) 
    {    
        parent::__construct($message, $code, $previous);
    }

    /**
     * Перехватывает исключение при невозможности дешифровать 
     * полученные данные, записает в лог.
     *
     * @param [string] $string
     * @return void
     */
    public function DecryptError($string) 
    {
        $string = Logger::addTime(). "\t" . trim($string). "\n";
        $string = File::write(Path::ERROR ,$string);
    }
}