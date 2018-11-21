<?php

spl_autoload_register(function ($class_name) {
    require_once "app/lib/" . $class_name . '.php';
});

Security::Map("/admin");

$Router = new Router();
$Router->Map("/", "TestController@index");
$Router->Map("/admin/panel", "TestController@index");
$Router->Map("/admin/kek", "TestController@index");
$Router->Map("/view/:id", "TestController@index");
$Router->Start();