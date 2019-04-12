<?php

namespace Janpa\App\Lib;

class ErrorHandler {

    /**
     * Renders standard janpa error page
     * @param string $title error title
     * @param string $message eror message to show
     * @param string $status status code
     */
    public static function error($errno, $errstr, $errfile = null, $errline = null) {
        // ob_end_clean();
        http_response_code(500);
            
        extract(array(
            "title" => $errstr,
            "file" => $errfile,
            "line" => $errline
        ));
        ob_start();
        require $_SERVER["DOCUMENT_ROOT"] . "/public/janpa/error.php";
        $render_view = ob_get_clean();
        echo $render_view;
        die();
    }

    /**
     * Handles and renders expcetion janpa page
     * @param Exception $exception
     */
    public static function exception($exception)
    {
        http_response_code(500);
        extract(array(
            "title" => $exception->getMessage(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine()
        ));
        ob_start();
        require $_SERVER["DOCUMENT_ROOT"] . "/public/janpa/error.php";
        $render_view = ob_get_clean();
        echo $render_view;
        die();
    }

    /**
     * Handles and renders fatal error janpa page
     */
    public static function fatalError()
    {
        http_response_code(500);
        $error = error_get_last();
        if($error["type"] === E_ERROR)
        {
            extract(array(
                "title" => $error["message"],
                "file" => $error["file"],
                "line" => $error["line"]
            ));
            ob_start();
            require $_SERVER["DOCUMENT_ROOT"] . "/public/janpa/error.php";
            $render_view = ob_get_clean();
            echo $render_view;
        }
        die();
    }
}