<?php

class Controller
{

    function __construct()
    {
        $this->Input = new Input();
    }

    /**
     * Created for extreme cases
     */
    public function load_all_models()
    {
        foreach (glob($_SERVER['DOCUMENT_ROOT'] ."/app/model/*.php") as $model)
        {
            require_once($model);
            $model_name = basename($model, ".php");
            $this->$model_name = new $model_name;
        }
    }

    /**
     * @param $model_name string model name to include / don't use .php
     */
    public function load_model($model_name)
    {
        if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/app/model/$model_name.php")) {
            require_once $_SERVER["DOCUMENT_ROOT"] . "/app/model/$model_name.php";
        } else {
            echo "Model named : $model_name not found";
            die;
        }
        $this->$model_name = new $model_name;
    }

    /**
     * view render with variables
     * @param string $file view file name
     * @param array $variables array with data
     */
    public function render($file, $variables = array())
    {
        if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/app/templates/" . $file . ".php")) {
            echo "view '$file' not found";
        } else {
            extract($variables);
            ob_start();
            require $_SERVER["DOCUMENT_ROOT"] . "/app/templates/" . $file . ".php";
            $render_view = ob_get_clean();
            echo $render_view;
        }
    }
}
