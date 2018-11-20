<?php

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

    /**
     * @param $controller controller name to check if exists
     * @return stdClass exists or nah
     */
    private function ControllerCheck($controller)
    {
        if (!class_exists($controller))
            die("$controller file not found");
        return new $controller;
    }

    /**
     * @param $name string path to controller file
     * @return bool exists or nah
     */
    private function FileCheck($name)
    {
        if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/app/controllers/$name.php"))
            die("$name file not found");
        return include_once $_SERVER["DOCUMENT_ROOT"] . "/app/controllers/$name.php";
    }

    /**
     * @param stdClass $controllerObject php class object
     * @param string $function function name
     * @return bool class contains function or nah
     */
    private function FunctionCheck($controllerObject, $function)
    {
        if (!method_exists($controllerObject, $function))
            die("$function not found in given object");
        return true;
    }

    /**
     * @param string $path url path like /users or smth
     * @param string $controller_func
     */
    public function Map($path, $controller_func)
    {
        $controller = explode("@", $controller_func)[0];
        $function = explode("@", $controller_func)[1];
        $params = explode("/", $controller_func);
        unset($params[0], $params[1]);
        $route = new Route("/" . explode("/", $path)[1], $controller, $function, $params);
        array_push($this->routes, $route);
    }

    /**
     * Iterate over routes, if found - execute else not found
     */
    public function Start()
    {
        $path = !empty($_GET["path"]) ? "/" . explode("/", $_GET["path"])[0] : "/";
        $full_path = !empty($_GET["path"]) ? explode("/", $_GET["path"]) : array();
        foreach ($this->routes as $route) {
            if ($route->path == $path) {
                //TODO security middleware here somehow
                Security::Verify($route->path);
                $this->FileCheck($route->controller);
                $controllerObject = $this->ControllerCheck($route->controller);
                $this->FunctionCheck($controllerObject, $route->function);
                foreach ($route->params as $id => $param) {
                    if (!empty($full_path[$id - 1])) {
                        array_push($this->method_params, $full_path[$id - 1]);
                    } else {
                        array_push($this->method_params, null);
                    }
                }
                call_user_func_array(array($controllerObject, $route->function), $this->method_params);
                die;
            }
        }
        //TODO error rendering
        echo "page not found";
    }
}