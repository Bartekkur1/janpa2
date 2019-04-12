<?php

namespace Janpa\App\Model;
use Janpa\App\Lib\Model as Model;

class User extends Model {
    public $table_name = "users";
    public $repository = "user";

    private $id;
    private $login;
    private $password;
    private $email;
    private $role;

    public function SetId($id) { $this->id = $id; }
    public function SetLogin($login) { $this->login = $login; }
    public function SetPassword($password) { $this->password = $password; }
    public function SetEmail($email) { $this->email = $email; }
    public function SetRole($role) { $this->role = $role; }

    public function GetId() { return $this->id; }
    public function GetLogin() { return $this->login; }
    public function GetPassword() { return $this->password; }
    public function GetEmail() { return $this->email; }
    public function GetRole() { return $this->role; }
}
