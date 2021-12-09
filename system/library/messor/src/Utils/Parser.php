<?php

namespace src\Utils;

use src\Exception\FileException;
use src\Config\Path;
use src\Utils\File;

class Parser
{
    /**
     * Массив в строку
     *
     * @param [array] $data
     * @return string
     */
    static function toStringURL($data)
    {
        $string = '';
        foreach ($data as $key => $value) {
            $key = trim($key);
            $value = trim($value);
            $string .= $key . '=' . urlencode($value) . "\n";
        }
        return $string;
    }

    /**
     *  Разбивает строку на массив по табуляции
     *
     * @param [string|array] $data
     * @return array
     */
    static function toArrayTab($data)
    {
        $dataArray = array();
        foreach ($data as $line) {
            $line = trim($line);
            $dataArray[] = explode("\t", trim($line));
        }
        return $dataArray;
    }

    static function toStringTab($data)
    {
        foreach ($data as &$line) {
            array_map('trim', $line);
            $line = implode("\t", $line);
        }
        return $data;
    }

    /**
     * Строка в массив по переводу строки
     *
     * @param [string] $data
     * @return array
     */
    static function toArray($data)
    {
        $dataArray = explode("\n", trim($data));
        return $dataArray;
    }

    static function toString($data)
    {
        $string = '';
        foreach ($data as $item) {
            $string .= $item . "\n";
        }
        $string = trim($string);
        return $string;
    }

    /**
     * Строку в массив, разделитель "="
     *
     * @param [string] $data
     * @return array
     */
    static function toArraySetting($data)
    {
        $data = explode("\n", $data);
        foreach ($data as $line) {
            $line = explode("=", $line);
            if (!empty($line[0])) {
                $request[trim($line[0])] = urldecode(trim($line[1]));
            }
        }
        if (isset($request)) {
            return $request;
        }
    }

    /**
     * Массив в строку, разделитель "="
     *
     * @param [string] $data
     * @return array
     */
    static function toSettingArray($data)
    {
        $request = '';
        foreach ($data as $key => $value) {
            $request .= trim($key) . ' = ' . trim(urlencode($value)) . "\n";
        }
        return $request;
    }

    /**
     * Получение версии базы данных
     *
     * @param string $string
     * @return string
     */
    static function versionDatabase($string)
    {
        $version = trim($string);
        if ($version != null) {
            $version = explode("_", trim($version));
            $version['version'] = $version[0];
            $version['hash'] = $version[1];
        } else {
            $version = '';
        }
        return $version;
    }

    /**
     * Распаковывает базу данных в дерево с ip адресами
     * 
     *
     * @param string $database
     * @return void
     */
    static function toArrayDatabase($database)
    {
        try {
            if (file_exists($database)) {
                $file = file($database);  // с PHP 5.6+ сравнение, не подверженное атаке по времени
                /*
                0 database version and info
                1 regularExp for block UserAgent (base64)
                2 .htaccess rules (honeypot)
                3 regularExp for block GET/POST (base64)
                4 ip
                ip
                ...
                */
                $tmp_ua = explode("\n", base64_decode(trim($file[1])));
                $return = array(
                    'version_string' => trim($file[0]),
                    'rules' => array(
                        'useragent' => array('attack' => $tmp_ua[3], 'tools' => $tmp_ua[0], 'search_engines' => $tmp_ua[1], 'bots' => $tmp_ua[2]),
                        'path'  => explode("\n", base64_decode(trim($file[2]))),
                        'request'  => explode("\n", base64_decode(trim($file[3])))
                    ),
                );
                // edit
                File::clear(Path::RULES);
                File::write(Path::RULES, serialize($return));
                $ip = array('ip' => array());
                unset($file[0]);
                unset($file[1]);
                unset($file[2]);
                unset($file[3]);

                natsort($file);
                foreach ($file as $line) {
                    $ip['ip'][] = trim($line);
                }
                File::deleteFilesInDir(Path::DB_TREE);
                for ($i = 0; $i < count($ip['ip']); $i++) {
                    if (self::ipFile($ip['ip'][$i])) {
                        File::write(Path::DB_TREE . self::ipFile($ip['ip'][$i]), $ip['ip'][$i] . "\n");
                    }
                }
                return $ip;
            } else {
                throw new FileException();
            }
        } catch (FileException $e) {
            $e->readError("File not found");
        }
    }

    /**
     * Получает первые 3 цифры ip адреса
     *
     * @param string $string
     * @return string
     */
    static function ipFile($string)
    {
        return substr($string, 0, strpos($string, '.'));
    }
}
