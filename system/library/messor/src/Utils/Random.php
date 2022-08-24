<?php

namespace src\Utils;

/**
 * Random Data Creation Class
 */
class Random 
{
    /**
     * Creating a random string
     *
     * @param int $min
     * @param int $max
     * @param string $available_sets
     * @return string
     */
    public static function Rand($min=5, $max=9, $available_sets = 'luds'){
        $length = rand($min, $max);
        $sets = array();
        if(strpos($available_sets, 'l') !== false)
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        if(strpos($available_sets, 'u') !== false)
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        if(strpos($available_sets, 'd') !== false)
            $sets[] = '23456789';
        if(strpos($available_sets, 's') !== false)
            $sets[] = '!@#$%&*?';
        $all = '';
        $password = '';
        foreach($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++){
            $password .= $all[array_rand($all)];
        }
        $password = str_shuffle($password);
        return $password;
    }
}