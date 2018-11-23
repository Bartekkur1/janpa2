<?php

class Controller extends Loader
{
    function __construct()
    {
        // testing ORM here
        $this->LoadLib("Input");
        ORM::Setup();
        $kek = new User();
        $kek->SetLogin("xd");
        $kek->SetPassword("123");
        ORM::Push($kek);
    }   

    /**
     * @param array $data array with data to parse intro json object
     * @param int $status http status code
     */
    public function JsonResponse($data = array(), $status = 200) 
    {
        http_response_code($status);
        echo json_encode($data);
    }

    /**
     * @param string $html_message html content to show on page
     * @param int $status http status code
     */
    public function Response($html_message, $status = 200) 
    {
        http_response_code($status);
        echo $html_message;
    }
 
    /**
     * view render with variables
     * @param string $file view file name
     * @param array $variables array with data
     */
    public function Render($file, $variables = array(), $status)
    {
        if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/app/templates/" . $file . ".php")) {
            ob_end_clean();
            ErrorHandler::ThrowNew("Template not found!",
            "Requested template '$file' could not be found " . debug_backtrace()[0]["file"] .
            " at line " . debug_backtrace()[0]["line"] . "" , 400);
        } else {
            http_response_code($status);
            extract($variables);
            ob_start();
            require $_SERVER["DOCUMENT_ROOT"] . "/app/templates/" . $file . ".php";
            $render_view = ob_get_clean();
            echo $render_view;
        }
    }
}
