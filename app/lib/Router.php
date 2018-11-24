<?php

namespace Janpa\App\Lib;

class Route
{
    public $path, $controller, $function, $params;

    /**
     * Route constructor.
     * @param $path @url path to controller
     * @param $controller controller name
     * @param $function controller method
     * @param $params @url params like /id
     */
    function __construct($path, $controller, $function, $params)
    {
        $this->path = $path;
        $this->controller = $controller;
        $this->function = $function;
        $this->params = $params;
    }
}

class Router
{
    private $Security;
    private $routes = array();
    private $method_params = array();

    function __construct($Security = null)
    {
        $this->Security = $Security;
    }

    /**
     * @param $controller controller name to check if exists
     * @return object exists or nah
     */
    private function ControllerCheck($controller)
    {
        if (!class_exists($controller))
            ErrorHandler::ThrowNew("Controller not found!",
            "Requested controller '$controller' could not be found " . debug_backtrace()[0]["file"] .
            " at line " . debug_backtrace()[0]["line"] . "" , 400);        
        return new $controller;
    }

    /**
     * @param $name string path to controller file
     * @return bool exists or nah
     */
    private function FileCheck($name)
    {
        if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/app/controllers/$name.php"))
            ErrorHandler::ThrowNew("File not found!",
            "Requested file '$file' could not be found " . debug_backtrace()[0]["file"] .
            " at line " . debug_backtrace()[0]["line"] . "" , 400);     
        return include_once $_SERVER["DOCUMENT_ROOT"] . "/app/controllers/$name.php";
    }

    /**
     * @param object $controllerObject php class object
     * @param string $function function name
     * @return bool class contains function or nah
     */
    private function FunctionCheck($controllerObject, $function)
    {
        if (!method_exists($controllerObject, $function))
            ErrorHandler::ThrowNew("Controller function not found!",
            get_class($controllerObject) . " does not contain method called '$function' check your routing", 400);     
        return true;
    }

    /**
     * @param string $path url path like /users or smth
     * @param string $controller_func
     */
    public function Map($path, $controller_func)
    {
        $params = array();
        $controller = explode("@", $controller_func)[0];
        $function = explode("@", $controller_func)[1];
        $path = preg_split('/\//', $path, 0, PREG_SPLIT_NO_EMPTY);
        foreach ($path as $key => $value) {
            if(preg_match('/(:)/', $value)) {
                array_push($params, ltrim($value, ":"));
                unset($path[$key]);
            }
        }
        $path = count($path) == 0 ? array("/") : $path;
        $route = new Route($path, $controller, $function, $params);
        array_push($this->routes, $route);
    }

    /**
     * Iterate over routes, if found - execute else not found
     */
    public function Start()
    {
        $path = isset($_GET["path"]) ? preg_split('/\//', $_GET["path"], 0, PREG_SPLIT_NO_EMPTY) : array("/");
        foreach($this->routes as $route) {
            foreach($route->params as $param) {
                array_pop($path);
            }
            if(count(array_diff_assoc($path, $route->path)) == 0 && count($path) > 0) {
                isset($this->Security) ? $this->Security->Verify($path) : "";
                $this->FileCheck($route->controller);
                $controllerObject = $this->ControllerCheck($route->controller);
                $this->FunctionCheck($controllerObject, $route->function);
                call_user_func_array(array($controllerObject, $route->function), array_diff($path, $route->path));
                die();
            }
        }
        ErrorHandler::ThrowNew("Error 404 not found", "Page you looking for doesn't exists", 404);
    }   
}