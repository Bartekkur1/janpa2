<?php

use Janpa\App\Lib\Controller as Controller;
use Janpa\App\Repository\UserRepository as UserRepository;
use Janpa\App\Model\User;

class DefaultController extends Controller {

    function __construct() {
        $this->LoadLib("Security");
        $this->LoadLib("Input");
        $this->LoadLib("ORM");
    }
    
    public function Login() 
    {
        if($form = $this->Input->Post()) 
        {
            if($this->Security->Authorize($form["login"], $form["password"]))
                $this->Redirect("/admin");
            echo "zły login lub hasło";
        }
        $this->Render("login");
    }
    
    public function Index() 
    {
        if(isset($_SESSION["user"])) 
        {
            var_dump(unserialize($_SESSION["user"]));
        }
        $this->Response("Im default controller, i like testing");
    }
    
    public function Register()
    {
        $error = "";
        $userRepo = new UserRepository(User::class);
        if($form = $this->Input->Post())
        {
            empty($form["login"]) ? $error = "Login nie może być pusty" : "";
            empty($form["password"]) ? $error = "Hasło nie może być puste" : "";
            empty($form["repeatPassword"]) ? $error = "Hasło nie może być puste" : "";
            empty($form["email"]) ? $error = "Email nie może być pusty" : "";

            if($form["password"] != $form["repeatPassword"])
                $error = "Hasła muszą się zgadzać!";

            if($userRepo->LoginTaken($form["login"]))
                $error = "Login jest już zajęty!";
            else if($userRepo->EmailTaken($form["email"]))
                $error = "Email jest już zajęty!";
            
            if(empty($error))
            {
                $user = new User();
                $user->SetLogin($form["login"]);
                $user->SetPassword(password_hash($form["password"], PASSWORD_BCRYPT));
                $user->SetEmail($form["email"]);
                $user->SetRole("ROLE_USER");
                if($this->ORM->Push($user))
                    $error = "Użytkownik zarejestrowany pomyślnie";
            }
        }
        $this->Render("register", ["error" => $error]);
    }

    public function Logout() 
    {
        $this->Security->Unauthenticate();
    }
    
    public function RoleCheck() 
    {
        $error = "";
        $user = unserialize($_SESSION["user"]);
        $userRepo = new UserRepository(User::class);

        if($form = $this->Input->Post())
        {
            if($form["email"] != $user->GetEmail())
            {
                if(empty($form["email"]))
                    $error = "Email nie może być pusty";
                else if($userRepo->EmailTaken($form["email"]))
                    $error = "Email jest już zajęty!";
                empty($error) ? $user->SetEmail($form["email"]) : "";
            }

            if($form["login"] != $user->GetLogin())
            {
                if(empty($form["login"]))
                    $error = "Login nie może być pusty";
                else if($userRepo->LoginTaken($form["login"]))
                    $error = "Login jest już zajęty!";
                empty($error) ? $user->SetLogin($form["login"]) : "";
            }

            if(!empty($form["password"]))
                $user->SetPassword(password_hash($form["password"], PASSWORD_BCRYPT));

            if(empty($error))
            {
                if($this->ORM->Push($user))
                {
                    $error = "Aktualizacja przebiegła pomyślnie";
                    $_SESSION["user"] = serialize($user);
                }
            }
        }
        
        $this->Render("panel", [
            "login" => $user->GetLogin(),
            "email" => $user->GetEmail(),
            "error" => $error
        ]);
    }
}


