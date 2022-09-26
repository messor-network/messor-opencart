<?php

namespace modules\fscheck;


class FSCheck
{
    private $path;
    private $files;
    private $dir;
    private $link;
    private $general;
    private $exclude;

    function setPath($path)
    {
        $this->path = $path;
    }

    function setExclude($exclude)
    {
        foreach ($exclude as $item) {
            $this->exclude[] = $this->path . '/' . $item;
        }
    }

    public function getResult()
    {
        $mainDir = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->path));
        foreach ($mainDir as $item) {
            if (!empty($this->exclude)) {
                foreach ($this->exclude as $exclude) {
                    if ($item->getRealPath() == $exclude) {
                        continue (2);
                    }
                }
            }
            $group = posix_getgrgid($item->getGroup());
            $owner = posix_getpwuid($item->getOwner());
            //if ($group['name'] != 'root' || $owner['name'] != 'root') {
                if ($item->isDir()) {
                    $perms = substr(sprintf('%o', $item->getPerms()), -4);
                    $path = str_replace($this->path, '', $item->getRealPath());
                    if ($path == '') { continue; }
                    $this->dir[$path] = $perms;
                    if ($perms !== '0755') {
                        if ($this->path == $item->getRealPath() || strlen($this->path) > strlen($item->getRealPath())) {
                            continue;
                        }
                        $this->general[str_replace($this->path, '', $item->getRealPath())] = $perms;
                    }
                } elseif ($item->isFile()) {
                    $perms = substr(sprintf('%o', $item->getPerms()), -4);
                    $this->files[str_replace($this->path, '', $item->getRealPath())] = $perms;
                    if ($perms !== '0644') {
                        $this->general[str_replace($this->path, '', $item->getRealPath())] = $perms;
                    }
                } elseif ($item->isLink()) {
                    $perms = substr(sprintf('%o', $item->getPerms()), -4);
                    $this->link[str_replace($this->path, '', $item->getRealPath())] = $perms;
                    if ($perms !== '0644') {
                        $this->general[str_replace($this->path, '', $item->getRealPath())] = $perms;
                    }
                }
            }
        //}
        return array('general' => $this->general);
    }

    public function getCountStatistic()
    {
        $dir = is_array($this->dir) ? count($this->dir) : 0;
        $file = is_array($this->files) ? count($this->files) : 0;
        if ($this->link > 0) {
            $link = count($this->link);
        } else {
            $link = 0;
        }
        $general = $this->general != null ? count($this->general) : 0;
        $sum = $dir + $file + $link;
        return array('dir' => $dir, 'files' => $file, 'link' => $link, 'sum' => $sum, 'general' => $sum);
    }
}
