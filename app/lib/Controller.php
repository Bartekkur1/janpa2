<?php

namespace Janpa\App\Lib;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'app/Lib/PhpMailer/Exception.php';
require 'app/Lib/PhpMailer/PHPMailer.php';
require 'app/Lib/PhpMailer/SMTP.php';

class Controller extends Loader
{
    public $error_msg, $success_msg;
    private $orm;

    function __construct()
    {
        $this->orm = new ORM();
    }

    public function Redirect($path, $delay = 0) {
        sleep($delay);
        header("Location: $path");
        die();
    }

    public function PhpMailerSetup() {
        $config = parse_ini_file("app/config.ini");
        $mail = new PHPMailer(true);
        $mail->isSMTP();                                     
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $config["email"];
        $mail->Password = $config["email_password"];                           
        $mail->SMTPSecure = 'tls';                            
        $mail->Port = 587;                                    
        $mail->SMTPDebug = false;
        $mail->CharSet = "UTF-8";
        $mail->setFrom('email@email.com', 'password');
        $mail->isHTML(true);
        return $mail;        
    }

    /**
     * @param string $name of the header
     * @return string header value
     */
    public function GetHeader($name) {
        return apache_request_headers()[$name];
    }

    /**
     * @param array $data array with data to parse intro json object
     * @param int $status http status code
     */
    public function JsonResponse($data = array(), $status = 200) 
    {
        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
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
    public function Render($file, $variables = array(), $status = 200)
    {
        if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/app/view/" . $file . ".php")) {
            ob_end_clean();
            throw new \Exception("Template $file not found - check /app/view");
        } else {
            http_response_code($status);
            extract($variables);
            ob_start();
            require $_SERVER["DOCUMENT_ROOT"] . "/app/view/" . $file . ".php";
            $render_view = ob_get_clean();
            echo $render_view;
        }
    }
}
