<?php

namespace Janpa\App\Lib;

class ErrorHandler {

    /**
     * Renders standard janpa error page
     * @param string $title error title
     * @param string $message eror message to show
     * @param string $status status code
     */
    public static function ThrowNew($title, $message, $status) {
        // ob_end_clean();
        http_response_code($status);
        extract(array(
            "title" => $title,
            "message" => $message,
            "status" => $status
        ));
        ob_start();
        require $_SERVER["DOCUMENT_ROOT"] . "/public/janpa/error.php";
        $render_view = ob_get_clean();
        echo $render_view;
        die();
    }
}