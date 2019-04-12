<?php

namespace Janpa\App\Lib;
use Janpa\App\Lib\QueryBuilder as QueryBuilder;

class Repository {

    public $qb;

    function __construct($class)
    {
        $this->object = new $class;
        $this->qb = new QueryBuilder();
    }
    
    function toModel($result, $object)
    {
        $this->columns = $this->qb->ShowColumns($this->object->table_name);
        foreach($this->columns as $column)
        {
            $function_name = "Set" . ucfirst($column);
            if(method_exists($object, $function_name))
                call_user_func(array($object, $function_name), $result->$column);
        }
        return $object;
    }

}