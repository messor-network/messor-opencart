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
        foreach($exclude as &$item) {
            $this->exclude[] = $this->path.'/'.$item;
        }
    }

    function getDefaultPath()
    {
        return dirname(DIR_APPLICATION);
    }

    public function getResult()
    {
        $mainDir = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->path));
        foreach ($mainDir as $item) {
            foreach($this->exclude as $exclude)
                if($item->getRealPath() == $exclude){
                    continue(2);
                }
            $this->general[$item->getRealPath()] = $item->getRealPath();
            $group = posix_getgrgid($item->getGroup());
            $owner = posix_getpwuid($item->getOwner());
            if ($group['name'] != 'root' || $owner['name'] != 'root') {
                if ($item->isDir()) {
                    $perms = substr(sprintf('%o', $item->getPerms()), -4);
                    if ($perms !== '0644') {
                        if ($this->path == $item->getRealPath() || strlen($this->path) > strlen($item->getRealPath())){
                            continue;
                        }
                        $this->dir[str_replace($this->path,'',$item->getRealPath())] = $perms;
                    }
                } elseif ($item->isFile()) {
                    $perms = substr(sprintf('%o', $item->getPerms()), -4);
                    if ($perms != '0755') {
                        $this->files[str_replace($this->path,'',$item->getRealPath())] = $perms;
                    }
                } elseif ($item->isLink()) {
                    $perms = substr(sprintf('%o', $item->getPerms()), -4);
                    if ($perms != '0755') {
                        $this->link[str_replace($this->path,'',$item->getRealPath())] = $perms;
                    }
                }
            }
        }
        return array('dir' => $this->dir, 'files' => $this->files, 'link' => $this->link);
    }

    public function getCountStatistic()
    {
        $dir = count($this->dir);
        $file = count($this->files);
        if ($this->link > 0) {
            $link = count($this->link);
        } else {
            $link = 0;
        }
        $general = count($this->general); 
        $sum = $dir + $file + $link;
        return array('dir' => $dir, 'files' => $file, 'link' => $link, 'sum' => $sum, 'general' => $general);
    }
    
}
