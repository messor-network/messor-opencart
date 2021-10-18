<?php

namespace src\Utils;

class File
{
    /**
     * Запись в файл
     *
     * @param [string] $name
     * @param [string] $string
     * @return bool
     */
    static function write($name, $string)
    {
        return file_put_contents($name, $string, FILE_APPEND | LOCK_EX);
    }

    /**
     * Создание пустого файла
     *
     * @param [string] $name
     * @return bool
     */
    static function create($name)
    {
        return file_put_contents($name, $string = null);
    }

    /**
     * Очистка файла
     * 
     * @param [string] $name
     * @return bool
     */
    static function clear($name)
    {
        return file_put_contents($name, '');
    }

    /**
     *  Чтение файла в строку
     *
     * @param [string] $name
     * @return bool
     */
    static function read($name)
    {
        if (file_exists($name)) {
            return file_get_contents($name);
        } else {
            self::create($name);
            return '';
        }
    }

    /**
     * Удаляет файлы в переданной директории
     *
     * @param [string] $dir
     * @return void
     */
    static function deleteFilesInDir($dir)
    {
        $openDir = opendir($dir);
        while ($file = readdir($openDir)) {
            if ($file != "." && $file != "..") {
                unlink($dir . $file);
            }
        }
        closedir($openDir);
    }
}
