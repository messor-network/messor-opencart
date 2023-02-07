<?php

namespace src\Utils;

use src\Exception\FileException;

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
        try {
            FileException::setErrorHandler();
            $file = file_put_contents($name, $string, FILE_APPEND | LOCK_EX);
            FileException::restoreErrorHandler();
            return $file;
        } catch (FileException $e) {
            $e->writeError();
            FileException::restoreErrorHandler();
        }

    }

    /**
     * Creating an empty file
     *
     * @param string $name
     * @return bool
     */
    static function create($name)
    {
        try {
            FileException::setErrorHandler();
            file_put_contents($name, $string = null);
            FileException::restoreErrorHandler();
        } catch (FileException $e) {
            $e->writeError();
            FileException::restoreErrorHandler();
        }
    }

    /**
     * Cleaning up a file
     * 
     * @param string $name
     * @return bool
     */
    static function clear($name)
    {
        try {
            FileException::setErrorHandler();
            file_put_contents($name, '');
            FileException::restoreErrorHandler();
        } catch (FileException $e) {
            $e->writeError();
            FileException::restoreErrorHandler();
        }
    }

    /**
     *  Reading a file into a string
     *
     * @param string $name
     * @return bool
     */
    static function read($name)
    {
        try {
            FileException::setErrorHandler();
            if (file_exists($name)) {
                $file = file_get_contents($name);
            } else {
                self::create($name);
                $file = '';
            }    
            FileException::restoreErrorHandler();
            return $file;
        } catch (FileException $e) {
            $e->writeError();
            FileException::restoreErrorHandler();
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
        try {
            FileException::setErrorHandler();
            $openDir = opendir($dir);
            while ($file = readdir($openDir)) {
                if ($file != "." && $file != "..") {
                    unlink($dir . $file);
                }
            }
            closedir($openDir);    
            FileException::restoreErrorHandler();
        } catch (FileException $e) {
            $e->writeError();
            FileException::restoreErrorHandler();
        }
    }
}
