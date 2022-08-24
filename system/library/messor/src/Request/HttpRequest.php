<?php

namespace src\Request;

/**
 * Class for working with GET, POST, COOKIE, SERVER, SESSION variables and stream
 */
class HttpRequest
{
    /**
     * Getting the global array $_GET;
     *
     * @return array
     */
    public function get()
    {
        return $_GET;
    }

    /**
     * Getting the global array $_COOKIE;
     *
     * @return array
     */
    public function cookie()
    {
        return $_COOKIE;
    }

    /**
     * Adds a value to the $_POST array
     *
     * @param string $name
     * @param string $value
     */
    public function setPost($name, $value)
    {
        $_POST[$name] = $value;
    }

    /**
     * Gets the $_POST global array or one of its values
     *
     * @param string|null $name
     * @return array|bool
     */
    public function post($name = null)
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
     * Adds a value to the $_SERVER array
     *
     * @param string $name
     * @param string $value
     * @return void
     */
    public function setServer($name, $value)
    {
        $_SERVER[$name] = $value;
    }

    /**
     * Gets the global array $_SERVER or one of its values
     *
     * @param string|null $name
     * @return array|bool
     */
    public function server($name = null)
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

    /**
     * Gets the $_SESSION array
     *
     * @param string|null $name
     * @return array|bool
     */
    public function session($name = null)
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
    
    /**
     * Adds a value to the $_SESSION array
     *
     * @param string $name
     * @param string $value
     * @return void
     */
    public function setSession($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * Gets a php stream
     *
     * @return string
     */
    public function stream()
    {
        $stream = fopen('php://input', 'r');
        return $stream;
    }
}
