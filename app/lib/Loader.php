<?php

namespace Janpa\App\Lib;

class Loader {

    /**
     * DON'T FORGET FILE EXTENSION
     * @param $file string file name to require from public directory
     */
    public static function LoadPublic($file) {
        if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/public/" . $file))
            require $_SERVER["DOCUMENT_ROOT"] .  "/public/" . $file;
        else throw new \Exception();
    }

    /**
     * auto include class to website
     * @param string $class_name to include
     */
    public static function ClassAutoload() {
        spl_autoload_register(function ($class_name) {
            $class_name = explode("\\", $class_name);
                if(file_exists("app/lib/" . end($class_name) . ".php"))
                    require_once "app/lib/" . end($class_name) . ".php";
                else if(file_exists("app/model/" . end($class_name) . ".php"))
                    require_once "app/model/" . end($class_name) . ".php";
                else if(file_exists("app/controllers/" . end($class_name) . ".php"))
                    require_once "app/controllers/" . end($class_name) . ".php";
                else if(file_exists("app/repository/" . end($class_name) . ".php"))
                    require_once "app/repository/" . end($class_name) . ".php";
                else throw new \Exception(end($class_name) . " not found, check /app/");
        });
    }

    /**
     * @param $name string to controller path
     * @return bool exists or nah
     */
    public static function LoadController($name) {
        if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/app/controllers/$name.php"))    
            return include_once $_SERVER["DOCUMENT_ROOT"] . "/app/controllers/$name.php";
        else throw new \Exception();
    }

    /**
     * Loads library to object instance
     * @param $lib_name string library name to include / don't use .php
     */
    public function LoadLib($lib_name)
    {
        if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/app/lib/$lib_name.php")) {
            $class_name = "Janpa\\App\\Lib\\" . $lib_name;
            $this->$lib_name = new $class_name;
        }
        else throw new \Exception();
    }
}