<?php

namespace Janpa\App\Lib;

class Input
{

    /**
     * Function to get $_POST input
     * @param string $name post input name
     * @return mixed input
     */
    public function Post($name = null)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($name)) {
                if (!empty($_POST[$name])) {
                    return $_POST[$name];
                }
            } else {
                return $_POST;
            }
        }
    }

    /**
     * Function to get $_GET input
     * @param string $name get input name
     * @return mixed input from get
     */
    public function Get($name = null)
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if (isset($name)) {
                if (!empty($_GET[$name])) {
                    return $_GET[$name];
                }
            } else {
                return $_GET;
            }
        }
    }

    /**
     * Function to get $_FILE input
     * @param string $name
     * @return resource file object
     */
    public function File($name = null)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($name)) {
                if (!empty($_FILES[$name])) {
                    return $_FILES[$name];
                }
            } else {
                return $_FILES;
            }
        }
    }
}
?>