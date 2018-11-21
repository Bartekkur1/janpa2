<?php

class Security extends Controller
{
    private $secured_paths = array();

    function __construct()
    {
        // $this->load_model("UserModel");
    }

    /**
     * @param $path string url path to link
     * @return void added path to secured list
     */
    public function Map($path)
    {
        $path = preg_split('/\//', $path, 0, PREG_SPLIT_NO_EMPTY);
        array_push($this->secured_paths, $path);
    }

    public function Authorize() {

    }

    /**
     * This function is for edition, here you can create your own authorize system.
     * @return bool authenticated or nah
     */
    private function Authenticate()
    {
        return isset($_SESSION["xd"]) && $_SESSION["xd"] == "xd";
    }

    /**
     * @param $path string url path
     */
    public function Verify($path)
    {
        foreach($this->secured_paths as $secured_path) {
            if(count(array_diff_assoc($secured_path, $path)) == 0) {
                if(!self::Authenticate())
                    ErrorHandler::ThrowNew("Permision denied", "You don't have permision to view this ", 401);
            }
        }
    }
}