<?php

namespace Janpa\App\Lib;
use Janpa\App\Model\User as User;

class Security
{
    private $secured_paths = array(), $roles = array();

    /**
     * @param string $path string url path to link
     */
    public function Map($path, $role)
    {
        $path = preg_split('/\//', $path, 0, PREG_SPLIT_NO_EMPTY);
        array_push($this->secured_paths, $path);
        array_push($this->roles, $role);
    }

    /**
     * Basic login authorize
     * @param string $login
     * @param string $password (hashed)
     */
    public function Authorize($login, $password) {
        $User = ORM::Load("User", array("login" => $login));
        if($User && password_verify($password, $User->GetPassword())) {
            $_SESSION["user"] = serialize($User);
            return true;
        } else
            return false;
    }

    public function Unauthenticate() {
        $_SESSION["user"] = null;
        header("Location: /login");
    }

    /**
     * This function is for edition, here you can create your own authorize system.
     * @return bool authenticated or nah
     */
    public function Authenticate($role)
    {
        $User = unserialize($_SESSION["user"]);
        if(empty($User))
            return false;
        $roles = explode(",", $User->GetRole());
        if(in_array($role, $roles))
            return true;
        else
            return false;
    }

    /**
     * Verifies that user have permision to access path 
     * @param string $path string url path
     * @return bool 
     */
    public function Verify($path)
    {
        foreach($this->secured_paths as $index => $secured_path) {
            if(count(array_diff_assoc($secured_path, $path)) == 0) {
                if(!self::Authenticate($this->roles[$index]))
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