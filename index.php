<?php

// Classes autoload
spl_autoload_register(function ($class_name) {
    if(file_exists("app/lib/" . $class_name . ".php"))
        require_once "app/lib/" . $class_name . '.php';
    else if(file_exists("app/model/" . $class_name . ".php"))
        require_once "app/model/" . $class_name . ".php";
    else {
        ob_end_clean();
        ErrorHandler::ThrowNew("Class not found!",
        "Requested class '$class_name' could not be found " . debug_backtrace()[1]["file"] .
        " at line " . debug_backtrace()[1]["line"] . "" , 400);     
    }
});

// Security info
$Security = new Security();
$Security->Map("/admin");

// App routing
$Router = new Router($Security);
$Router->Map("/", "TestController@index");
$Router->Start();