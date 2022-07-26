<?php

namespace src\Exception;

use src\Logger\Logger;
use src\Utils\File;
use src\Config\Path;

/**
 * Класс исключений при работе с файлами
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
     * Исключение чтение файла 
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
     * Ошибка записи в файл
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