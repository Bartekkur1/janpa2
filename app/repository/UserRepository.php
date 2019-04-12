<?php

namespace Janpa\App\Repository;
use Janpa\App\Lib\Repository as Repository;

class UserRepository extends Repository {

    public $columns, $object;

    function __construct($class)
    {
        parent::__construct($class);
    }

    public function FindAdmin()
    {
        $this->qb->Select("Users");
        $this->qb->Where(["role" => "ROLE_ADMIN"]);
        $this->qb->Limit(0, 1);
        $result = $this->qb->Execute();
        return $this->toModel($result[0], $this->object);
    }

    public function LoginTaken($login)
    {
        return $this->qb->Exists("Users", ["login" => $login]);
    }

    public function EmailTaken($email)
    {
        return $this->qb->Exists("Users", ["email" => $email]);
    }

}