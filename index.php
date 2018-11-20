<?php
    require "app/lib/Router.php";

    $Router = new Router();
    $Router->Map("/", "TestController@index");
    $Router->Start();