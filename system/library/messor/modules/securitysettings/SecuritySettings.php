<?php

namespace modules\securitysettings;

class SecuritySettings
{
    private $list;
    private $version;

    public function __construct()
    {
        $mainDir = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(dirname(DIR_APPLICATION)));
        foreach ($mainDir as $item) {
            $this->list[] = $item->getRealPath();
        }
        array_unique($this->list);
    }


    public function checkAdminPanel()
    {
        $check = explode('/', DIR_APPLICATION);
        array_pop($check);
        if (end($check) == 'admin') {
            return true;
        } else {
            return false;
        }
    }

    public function checkAdminLogin($users)
    {
        foreach ($users as $item) {
            if ($item['novalid']) {
                return true;
            } 
        }
        return false;
    }

    public function getVersionOpencart() 
    {
        return $this->version;
    }

    public function checkLastVersionOpencart($versionCurrent)
    {
        $url = "https://github.com/opencart/opencart/releases/latest";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $a = curl_exec($ch);
        curl_close($ch);
        $headers = explode("\n", $a);
        foreach ($headers as $item) {
            if (strpos($item, "location:") !== false) {
                $match = trim(str_replace("Location:", "", $item));
                $match = explode('/', $match);
                $this->version = end($match);
                break;
            }
        }
        if ($versionCurrent != $this->version) {
            return true;
        } else {
            return false;
        }
    }


    public function checkConfigPerms()
    {
        $result = false;
        $configPerms = array();
        foreach ($this->list as $item) {
            $path = explode('/', $item);
            if (end($path) == 'config.php') {
                $perms = substr(sprintf('%o', fileperms($item)), -4);
                if ($perms != "0755") {
                    $result = true;
                }
                $configPerms[$item] = $perms;
            }
        }
        return array($configPerms, $result);
    }

    public function checkInstallDirectory()
    {
        $check = dirname(DIR_APPLICATION) . '/install';
        if (is_dir($check)) {
            return true;
        } else {
            return false;
        }
    }

    public function checkDBPrefix($prefix)
    {
        if ($prefix == "oc_") {
            return true;
        } else {
            return false;
        }
    }

    public function checkShowError($show)
    {
        if($show == 0) {
            return false;
        } else {
            return true;
        }
    }
}
