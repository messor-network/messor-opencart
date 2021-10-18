<?php 

namespace src\Request;

class HttpRequest
{
    /**
     * Получение глобального массива $_GET;
     *
     * @return array
     */
    public function get()
    {
        return $_GET;
    }

    /**
     * Получение глобального массива $_COOKIE;
     *
     * @return array
     */
    public function cookie()
    {
        return $_COOKIE;
    }

    /**
     * Добавляет значение в массив $_POST
     *
     * @param [string] $name
     * @param [string] $value
     */
    public function setPost($name, $value) 
    {
        $_POST[$name] = $value;
    }

    /**
     * Получает глобальный массив $_POST или один из его значений
     *
     * @param [string] $name
     * @return array
     */
    public function post($name=null)
    {
        if (!is_null($name)) {
            if (isset($_POST[$name])) {
                return $_POST[$name];
            } else {
                return false;
            }
        }
        return $_POST;
    }

    /**
     * Добавляет значение в массив $_SERVER
     *
     * @param [string] $name
     * @param [string] $value
     */
    public function setServer($name, $value) 
    {
        $_SERVER[$name] = $value;
    }

    /**
     * Получает глобальный массив $_SERVER или один из его значений
     *
     * @param [string] $name
     * @return array
     */
    public function server($name=null) 
    {
        if (!is_null($name)) {
            if (isset($_SERVER[$name])) {
                return $_SERVER[$name];
            } else {
                return false;
            }
        }
        return $_SERVER;
        
    }

    public function session($name=null) 
    {
        if (!is_null($name)) {
            if (isset($_SESSION[$name])) {
                return $_SESSION[$name];
            } else {
                return false;
            }
        }
        return $_SESSION;
        
    }

    public function setSession($name, $value) 
    {
        $_SESSION[$name] = $value;
    }

    /**
     * Получает поток php
     *
     * @return stream
     */
    public function stream()
    {
        $stream = fopen('php://input', 'r');
        return $stream;
    }

}