<?php

namespace src\Exception;

use src\Logger\Logger;
use src\Utils\File;
use src\Config\Path;

/**
 * File handling exception class
 */
class FileException extends \Exception {

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
     * Exception while reading file
     *
     * @param string $string
     * @return void
     */
    public function readError($string) 
    {
        $string = Logger::addTime(). "\t" . trim($string). "\n";
        $string = File::write(Path::ERROR ,$string);
    }

    /**
     * Error writing to file
     *
     * @param string $string
     * @return void
     */
    public function writeError($string) 
    {
        $string = Logger::addTime(). "\t" . trim($string). "\n";
        $string = File::write(Path::ERROR ,$string);
    }
}            