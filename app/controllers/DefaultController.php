<?php

use Janpa\App\Lib\Controller as Controller;
use Janpa\App\Repository\UserRepository as UserRepository;
use Janpa\App\Model\User;

class DefaultController extends Controller {

    function __construct() {
        $this->LoadLib("Security");
        $this->LoadLib("Input");
    }
    
    public function Login() {
        if($form = $this->Input->Post()) {
            if($this->Security->Authorize($form["login"], $form["password"]))
                $this->Redirect("/admin");
            echo "zły login lub hasło";
        }
        $this->Render("login");
    }
    
    public function Index() {
        if(isset($_SESSION["user"])) {
            var_dump(unserialize($_SESSION["user"]));
        }
        $this->Response("Im default controller, i like testing");
    }
    
    public function Logout() {
        $this->Security->Unauthenticate();
    }
    
    public function RoleCheck() {
        $userRepo = new UserRepository(User::class);
        $admin = $userRepo->FindAdmin();
        echo $admin->GetEmail();
    }
}


