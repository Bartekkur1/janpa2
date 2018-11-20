<?php
require "app/lib/Security.php";
require "app/lib/Controller.php";
require "app/lib/Router.php";

Security::Map("/admin");

$Router = new Router();
$Router->Map("/", "TestController@index");
$Router->Map("/admin/panel", "TestController@index");
$Router->Map("/admin/kek", "TestController@index");
$Router->Map("/admin/dupa", "TestController@index");

$Router->Start();