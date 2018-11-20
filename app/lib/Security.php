<?php

class Security
{
    private static $secured_paths = array();

    /**
     * @param $path string url path to link
     * @return void added path to secured list
     */
    public static function Map($path)
    {
        array_push(self::$secured_paths, $path);
    }

    /**
     * This function is for edition, here you can create your own authorize system.
     * @return bool authenticated or nah
     */
    private static function Authenticate()
    {
        return isset($_SESSION["xd"]) && $_SESSION["xd"] == "xd";
    }

    /**
     * @param $path string url path
     */
    public static function Verify($path)
    {
        foreach(self::$secured_paths as $secured_path) {
            if($path == $secured_path) {
                if(!self::Authenticate()) {
                    die("Permision Denied");
                }
            }
        }
    }
}