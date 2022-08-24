<?php

namespace src\Utils;

/**
 * Class for file operations
 */
class File
{
    /**
     * Write to file
     *
     * @param string $name
     * @param string $string
     * @return bool
     */
    static function write($name, $string)
    {
        return file_put_contents($name, $string, FILE_APPEND | LOCK_EX);
    }

    /**
     * Creating an empty file
     *
     * @param string $name
     * @return bool
     */
    static function create($name)
    {
        return file_put_contents($name, $string = null);
    }

    /**
     * Cleaning up a file
     * 
     * @param string $name
     * @return bool
     */
    static function clear($name)
    {
        return file_put_contents($name, '');
    }

    /**
     *  Reading a file into a string
     *
     * @param string $name
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
     * Deletes files in the given directory
     *
     * @param string $dir
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
