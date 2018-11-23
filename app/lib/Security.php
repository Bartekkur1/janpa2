<?php

class Security extends Controller
{
    private $secured_paths = array();

    /**
     * @param string $path string url path to link
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
        return isset($_SESSION["key"]) && $_SESSION["key"] == "123";
    }

    /**
     * Verifies that user have permision to access path 
     * @param string $path string url path
     * @return bool 
     */
    public function Verify($path)
    {
        foreach($this->secured_paths as $secured_path) {
            if(count(array_diff_assoc($secured_path, $path)) == 0) {
                if(!self::Authenticate())
                    ErrorHandler::ThrowNew("Permision denied", "You don't have permision to view this ", 401);
                else
                    return true;
            }
        }
    }

    /**
     * Function to secure your password - opened for change
     * @param string $password
     * @return string $hashed_password
     */
    public static function Password($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}