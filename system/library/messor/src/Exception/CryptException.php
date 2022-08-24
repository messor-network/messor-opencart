<?php

namespace src\Exception;

use src\Logger\Logger;
use src\Utils\File;
use src\Config\Path;

/**
 * Encryption exception class
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
     * Intercepts an exception when it is impossible to decrypt the received data,
     * writes it to the log.
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