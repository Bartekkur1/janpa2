<?php

namespace Janpa;
session_start();
include "App/Lib/Loader.php";
use Janpa\App\Lib\Loader as Loader;
use Janpa\App\Lib\Security as Security;
use Janpa\App\Lib\Router as Router;
use Janpa\App\Lib\ErrorHandler as ErrorHandler;

Loader::ClassAutoload();

// Security info
$Security = new Security();
$Security->Map("/admin", "ROLE_ADMIN");   

// App routing
$Router = new Router($Security);
$Router->Map("/", "DefaultController@Index");
$Router->Map("/login", "DefaultController@Login");
$Router->Map("/admin", "DefaultController@Index");
$Router->Map("/logout", "DefaultController@Logout");

$Router->Start();