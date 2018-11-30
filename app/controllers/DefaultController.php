<?php

use Janpa\App\Lib\Controller as Controller;

class DefaultController extends Controller {

    public function Index() {
        $User = unserialize($_SESSION["user"]);
        var_dump($User);
        $this->Response("Im default controller, i like testing");
    }

    public function Logout() {
        $this->Security->Unauthenticate();
    }

    public function RoleCheck() {
        $this->Response("You have permision to view this");
    }

    public function Login() {
        if($form = $this->Input->Post()) {
            echo $this->Security->Authorize($form["login"], $form["password"]) ? "logged in" : "failed";
        }
        $this->render("login");
    }
}