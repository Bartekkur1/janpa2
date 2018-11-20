<?php

require_once "Loader.php";

class Controller extends Loader
{

    function __construct()
    {
        $this->load_lib("Input");
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
