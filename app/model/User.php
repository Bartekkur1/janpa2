<?php

namespace Janpa\App\Model;
use Janpa\App\Lib\Model as Model;

class User extends Model {
    protected $id;
    protected $login;
    protected $password;
    protected $email;
    protected $role;

    public function SetId($id) {
        $this->id = $id;
    }

    public function GetId() {
        return $this->id;
    }

    public function SetLogin($login) {
        $this->login = $login;
    }

    public function GetLogin() {
        return $this->login;
    }

    public function SetPassword($password) {
        $this->password = $password;
    }

    public function GetPassword() {
        return $this->password;
    }

    public function GetEmail() {
        return $this->email;
    }

    public function SetEmail($email) {
        $this->email = $email;
    }

    public function GetRole() {
        return $this->role;
    }

    public function SetRole($role) {
        $this->role = $role;
    }
}