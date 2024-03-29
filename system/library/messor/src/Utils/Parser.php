<?php

namespace src\Utils;

use src\Exception\ParserException;
use src\Config\Path;
use src\Utils\File;

/**
 * Parsing class for messor
 */
class Parser
{
    /**
     * array to string
     *
     * @param array $data
     * @return string
     */
    static function toStringURL($data)
    {
        try {
            ParserException::setErrorHandler();
        $string = '';
        foreach ($data as $key => $value) {
            $key = trim($key);
            $value = isset($value) ? urlencode(trim($value)) : null;
            $string .= $key . '=' . $value . "\n";
        }
            ParserException::restoreErrorHandler();
        } catch (ParserException $e) {
            $e->parsingError();
            ParserException::restoreErrorHandler();
        }
        return $string;
    }

    /**
     *  String to array, delimiter "\t"
     *
     * @param string|array $data
     * @return array
     */
    static function toArrayTab($data)
    {
        try {
            ParserException::setErrorHandler();
        $dataArray = array();
        foreach ($data as $line) {
            $line = trim($line);
            $dataArray[] = explode("\t", trim($line));
        }
            ParserException::restoreErrorHandler();
        } catch (ParserException $e) {
            $e->parsingError();
            ParserException::restoreErrorHandler();
        }
        return $dataArray;
    }

    /**
     * Array to string, delimiter "\t"
     *
     * @param string|array $data
     * @return array
     */
    static function toStringTab($data)
    {
        try {
            ParserException::setErrorHandler();
        foreach ($data as &$line) {
            array_map('trim', $line);
            $line = implode("\t", $line);
        }
            ParserException::restoreErrorHandler();
        } catch (ParserException $e) {
            $e->parsingError();
            ParserException::restoreErrorHandler();
        }
        return $data;
    }

    /**
     * String to array, delimiter "\n"
     *
     * @param string $data
     * @return array
     */
    static function toArray($data)
    {
        try {
            ParserException::setErrorHandler();
        $dataArray = explode("\n", trim($data));
            ParserException::restoreErrorHandler();
        } catch (ParserException $e) {
            $e->parsingError();
            ParserException::restoreErrorHandler();
        }
        return $dataArray;
    }

    /**
     * Array to string, delimiter "\n"
     *
     * @param array $data
     * @return string
     */
    static function toString($data)
    {
        try {
            ParserException::setErrorHandler();
        $string = '';
        foreach ($data as $item) {
            $string .= $item . "\n";
        }
        $string = trim($string);
            ParserException::restoreErrorHandler();
        } catch (ParserException $e) {
            $e->parsingError();
            ParserException::restoreErrorHandler();
        }
        return $string;
    }

    /**
     * String to array, delimiter "="
     *
     * @param string $data
     * @return array
     */
    static function toArraySetting($data)
    {
        try {
            ParserException::setErrorHandler();
        $data = explode("\n", $data);
        foreach ($data as $line) {
            $line = explode("=", $line);
            if (!empty($line[0])) {
                $line[1] = isset($line[1]) ? urldecode(trim($line[1])) : null;
                $request[trim($line[0])] = $line[1];
            }
        }
            ParserException::restoreErrorHandler();
        } catch (ParserException $e) {
            $e->parsingError();
            ParserException::restoreErrorHandler();
        }
        if (isset($request)) {
            return $request;
        }
    }

    /**
     * Array to string, delimiter "="
     *
     * @param array $data
     * @return string
     */
    static function toSettingArray($data)
    {
        try {
            ParserException::setErrorHandler();
        $request = '';
        foreach ($data as $key => $value) {
                $value = $value ? $value : '';
            $request .= trim($key) . ' = ' . trim(urlencode($value)) . "\n";
        }
            ParserException::restoreErrorHandler();
        } catch (ParserException $e) {
            $e->parsingError();
            ParserException::restoreErrorHandler();
        }
        return $request;
    }

    static function toArraySettingTab($data)
    {
        try {
            ParserException::setErrorHandler();
        $data = explode("\n", $data);
        foreach ($data as $keyData => $lineTab) {
            $line = explode("\t", $lineTab);
            foreach ($line as $item) {
                if ($item != false) list($key, $value) = explode("=", $item); 
                if (!empty($key)) {
                    $value = isset($value) ? urldecode(trim($value)) : 0;
                    $request[$keyData][trim($key)] = trim($value);
                    $key = false;
                }
            }
        }
            ParserException::restoreErrorHandler();
        } catch (ParserException $e) {
            $e->parsingError();
            ParserException::restoreErrorHandler();
        }
        if (isset($request)) {
            return $request;
        }
    }

    static function toSettingArrayTab($data)
    {
        try {
            ParserException::setErrorHandler();
        $request = '';
        foreach ($data as $item) {
                $size = count($item);
                $i = 0;
            foreach ($item as $key => $value) {
                    $i++;
                    if ($i != $size) {
                        $request .= trim($key) . ' = ' . trim(urlencode($value)) . "\t";
                } else {
                        $request .= trim($key) . ' = ' . trim(urlencode($value)) . "\n";
                }
            }
        }
            ParserException::restoreErrorHandler();
        } catch (ParserException $e) {
            $e->parsingError();
            ParserException::restoreErrorHandler();
        }
        return $request;
    }

    /**
     * Get the database version
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
     * Unpacks the database into a tree with ip addresses
     * 
     * @param string $database
     * @return void
     */
    static function toArrayDatabase($database)
    {
        $file = Parser::toArray(File::read($database));
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
                        'useragent' => array(
                            'attack' => $tmp_ua[3], 
                            'tools' => $tmp_ua[0], 
                            'search_engines' => $tmp_ua[1], 
                            'bots' => $tmp_ua[2],
                            'social' => $tmp_ua[4],
                        ),
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
                    if (self::ipv4File($ip['ip'][$i])) {
                        File::write(Path::DB_TREE . self::ipv4File($ip['ip'][$i]), $ip['ip'][$i] . "\n");
                    }
                }
                return $ip;
    }

    /**
     * Gets the first 3 digits of an ip address
     * 
     *
     * @param string $string
     * @return string
     */
    static function ipv4File($string)
    {
        return substr($string, 0, strpos($string, '.'));
    }


    /**
     * Gets the first 4 digits of an ip address
     *
     * @param string $string
     * @return string
     */
    static function ipv6File($string)
    {
        return substr($string, 0, strpos($string, ':'));
    }
}
