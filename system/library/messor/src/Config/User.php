<?php

namespace src\Config;

use src\Utils\File;
use src\Utils\Parser;
use src\Config\Path;

/**
 * Данные пользователя в сети messor
 */
class User
{
    public static $networkID;
    public static $networkPassword;
    public static $encryptionAlg;
    public static $encryptionKey;
    public static $salt;
    public static $adminLogin;
    public static $adminPassword;
    public static $userAgent;

    /**
     * Инициализация данных пользователя
     *
     * @return void
     */
    public static function init()
    {
        if (file_exists(Path::USERCONF)) {
            $user = Parser::toArraySetting(File::read(Path::USERCONF));
            self::$networkID = $user['Network_id'];
            self::$networkPassword = $user['Network_password'];
            self::$encryptionAlg = $user['Encryption_alg'];
            self::$encryptionKey = $user['Encryption_key'];
            self::$salt = $user['Mess_salt'];
            self::$adminLogin = $user['Admin_login'];
            self::$adminPassword = $user['Admin_password'];
            self::$userAgent = $user['Useragent'];
        }
    }
}
User::init();
