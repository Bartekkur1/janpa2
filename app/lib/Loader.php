<?php

namespace Janpa\App\Lib;

class Loader {

    /**
     * DON'T FORGET FILE EXTENSION
     * @param $file string file name to require from public directory
     */
    public static function LoadPublic($file) {
        if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/public/" . $file)) {
            require $_SERVER["DOCUMENT_ROOT"] .  "/public/" . $file;
        } else {
            ErrorHandler::ThrowNew("Public file not found!",
            "Requested public file '$lib_name' could not be found " . debug_backtrace()[0]["file"] .
            " at line " . debug_backtrace()[0]["line"] . "" , 400);   
        }
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
            else
                ErrorHandler::ThrowNew("Class not found!",
                "Requested class '" .end($class_name). "' could not be found " . debug_backtrace()[1]["file"] .
                " at line " . debug_backtrace()[1]["line"] . "" , 400);     
        });
    }

    /**
     * @param $name string to controller path
     * @return bool exists or nah
     */
    public static function LoadController($name) {
        if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/app/controllers/$name.php"))
            ErrorHandler::ThrowNew("File not found!",
            "Requested file '$name' could not be found " . debug_backtrace()[0]["file"] .
            " at line " . debug_backtrace()[0]["line"] . "" , 400);     
        return include_once $_SERVER["DOCUMENT_ROOT"] . "/app/controllers/$name.php";
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
        else 
            ErrorHandler::ThrowNew("Library not found!",
            "Requested library '$lib_name' could not be found " . debug_backtrace()[0]["file"] .
            " at line " . debug_backtrace()[0]["line"] . "" , 400);   
    }
}