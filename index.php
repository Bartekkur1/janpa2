<?php

// Classes autoload
spl_autoload_register(function ($class_name) {
    require_once "app/lib/" . $class_name . '.php';
});

// Security info
$Security = new Security();
$Security->Map("/admin");

// App routing
$Router = new Router($Security);
$Router->Map("/", "TestController@index");
$Router->Start();