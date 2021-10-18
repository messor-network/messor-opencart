<?php

namespace src\Crypt;

interface iCrypt 
{
    public function Encrypt($data);
    public function Decrypt($data);
}