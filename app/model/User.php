<?php

class User extends Model {
    protected $id;
    protected $login;
    protected $password;

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
}