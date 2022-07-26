<?php


namespace src\Console;

use src\Utils\Parser;
use src\Config\Path;
use src\Utils\File;

class Settings
{
    private static $settings;

    public static function init($argv)
    {
        self::$settings = Parser::toArraySetting(File::read(Path::SETTINGS));
        $method = $argv[1];
        if ($method == "get") {
            self::getSettings($argv[2]);
        } else if ($method == "set") {
            self::setSettings($argv[2], $argv[3]);
        } else if ($method == "show") {
            self::showSettings();
        }
        self::saveSettings();
    }

    public static function showSettings()
    {
        foreach (self::$settings as $k => $v) {
            echo $k ."=". $v."\n";
        }
    }

    public static function getSettings($arg) {
        if (isset(self::$settings[$arg])) {
            echo self::$settings[$arg];
        } else {
            echo "setting not found\n";
        }
    }

    public static function setSettings($arg, $value) {
        if (isset(self::$settings[$arg])) {
            self::$settings[$arg] = $value;
            echo "setting update\n";
        } else {
            echo "setting not found\n";
        }
    }

    public static function saveSettings()
    {
        self::modificationBlockDdos();
        File::clear(Path::SETTINGS);
        File::write(Path::SETTINGS, Parser::toSettingArray(self::$settings));
    }

    public static function modificationBlockDdos()
    {
        if (self::$settings["block_ddos"] == 0) {
            $white = Parser::toArraySetting(File::read(Path::WHITE_LIST));
            if (!empty($white)) {
                foreach ($white as $key => $value) {
                    if ($value == "ddos") {
                        unset($white[$key]);
                    }
                }
                File::clear(Path::WHITE_LIST);
                File::write(Path::WHITE_LIST, Parser::toSettingArray($white));
            }
        }
        if (self::$settings["block_ddos"] == 1) {
            self::$settings["lock"] = "js_unlock";
        }
    }
}

