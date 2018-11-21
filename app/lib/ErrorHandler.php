<?php

class ErrorHandler {

    /**
     * @param $message string eror message to show
     */
    public static function ThrowNew($title, $message, $status) {
        self::Render(array(
            "title" => $title,
            "message" => $message,
            "status" => $status
        ));
    }

    /**
     * Renders error page with given variables
     */
    private function Render($variables = array()) {
        http_response_code($variables["status"]);
        extract($variables);
        ob_start();
        require $_SERVER["DOCUMENT_ROOT"] . "/public/janpa/error.php";
        $render_view = ob_get_clean();
        echo $render_view;
        die();
    }

}