<?php

namespace messor;

define('BASE_PATH', __DIR__);

class Autoloader
{
    public static function register()
    {
        spl_autoload_register(function ($class) {
            $class = '\\' . $class;
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
            $file = __DIR__ . $file;
            if (file_exists($file)) {
                require_once $file;
                return true;
            }
            return false;
        });
    }
}
Autoloader::register(); 