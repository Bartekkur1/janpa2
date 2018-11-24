<?php

namespace Janpa;
use Janpa\App\Lib\Security as Security;
use Janpa\App\Lib\Router as Router;
use Janpa\App\Lib\ErrorHandler as ErrorHandler;

// Classes autoload
spl_autoload_register(function ($class_name) {
    $class_name = explode("\\", $class_name);
    if(file_exists("app/lib/" . end($class_name) . ".php"))
        require_once "app/lib/" . end($class_name) . ".php";
    else if(file_exists("app/model/" . end($class_name) . ".php"))
        require_once "app/model/" . end($class_name) . ".php";
    else
        ErrorHandler::ThrowNew("Class not found!",
        "Requested class '" .end($class_name). "' could not be found " . debug_backtrace()[1]["file"] .
        " at line " . debug_backtrace()[1]["line"] . "" , 400);     
});


// Security info
$Security = new Security();
$Security->Map("/admin");

// App routing
$Router = new Router($Security);
$Router->Map("/", "TestController@index");
$Router->Start();