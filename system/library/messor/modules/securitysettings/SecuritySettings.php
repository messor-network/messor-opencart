<?php

namespace modules\securitysettings;

class SecuritySettings
{
    private $list;
    private $version;

    public function __construct($path)
    {
        $mainDir = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        foreach ($mainDir as $item) {
            $this->list[] = $item->getRealPath();
        }
        array_unique($this->list);
    }


    public function checkAdminPanel($fullPath, $adminPanelName)
    {
        $check = explode('/', $fullPath);
        array_pop($check);
        if (end($check) == $adminPanelName) {
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

    public function checkConfigPerms($name)
    {
        $result = false;
        $configPerms = array();
        foreach ($this->list as $item) {
            $path = explode('/', $item);
            if (end($path) == $name) {
                $perms = substr(sprintf('%o', fileperms($item)), -4);
                if ($perms != "0644") {
                    $result = true;
                }
                $configPerms[$item] = $perms;
            }
        }
        return array($configPerms, $result);
    }

    public function checkInstallDirectory($pathInstall)
    {
        if (is_dir($pathInstall)) {
            return true;
        } else {
            return false;
        }
    }

    public function checkDBPrefix($prefix, $prefixForCMS)
    {
        if ($prefix == $prefixForCMS) {
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
