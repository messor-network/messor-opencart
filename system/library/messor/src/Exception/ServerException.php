<?php

namespace src\Exception;

use src\Logger\Logger;
use src\Utils\File;
use src\Config\Path;

/**
 * Класс исключений при работе с сервером
 */
class ServerException extends \Exception {

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
     * Исключение запросов к серверу
     *
     * @param string $string
     * @return void
     */
    public function ServerError($string) 
    {
        $string = Logger::addTime(). "\t" . trim($string). "\n";
        $string = File::write(Path::ERROR ,$string);
    }
}