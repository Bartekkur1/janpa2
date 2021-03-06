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
            throw new \Exception("Controller $controller not found");
        return new $controller;
    }

    /**
     * @param object $controllerObject php class object
     * @param string $function function name
     * @return bool class contains function or nah
     */
    private function FunctionCheck($controllerObject, $function)
    {
        if (!method_exists($controllerObject, $function))
            throw new \Exception();
            // ErrorHandler::error("Controller function not found!",
            // get_class($controllerObject) . " does not contain method called '$function' check your routing", 400);     
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
        foreach($this->routes as $route) {
            $path = isset($_GET["path"]) ? preg_split('/\//', $_GET["path"], 0, PREG_SPLIT_NO_EMPTY) : array("/");
            $original_path = $path;
            $params = array_diff_assoc($path, $route->path);
            foreach($route->params as $param) {
                array_pop($path);
            }
            if(count(array_diff_assoc($path, $route->path)) == 0 && count($path) > 0) {
                if(count($params) == count($route->params)) {
                    isset($this->Security) ? $this->Security->Verify($path) : "";
                    Loader::LoadController($route->controller);
                    $controllerObject = $this->ControllerCheck($route->controller);
                    $this->FunctionCheck($controllerObject, $route->function);
                    call_user_func_array(array($controllerObject, $route->function), $params);
                    die();
                }
            }
        }
        throw new \Exception("Error 404, page not found");
    }   
}