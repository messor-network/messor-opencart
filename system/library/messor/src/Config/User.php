<?php

namespace src\Config;

use src\Utils\File;
use src\Utils\Parser;
use src\Config\Path;

/**
 * User class in messor network
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
     * User data initialization
     *
     * @return void
     */
    public static function init()
    {
        if (!empty(file_get_contents(Path::USERCONF))) {
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
