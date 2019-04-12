<?php

namespace Janpa\App\Lib;

class ORM {

    private $qb;

    /**
     * Seting up database connection on query builder
     */
    // function Setup()
    // {
    //     $this->qb = new QueryBuilder();
    // }
    
    function __construct() {
        $this->qb = new QueryBuilder();
    }

    /**
     * @param string $table_name 
     * @param array $params where to delete
     * @return bool success
     */
    public function Delete($table_name, array $params) 
    {
        $table_name .= "s";
        if($this->qb->Exists($table_name, $params)) {
            $this->qb->Delete($table_name);
            $this->qb->Where($params);
            return $this->qb->Execute() ? true : false;    
        } else {
            return false;
        }
    }

    /**
     * TODO arguments implementation
     * Returns object founded in db / leave array empty to load all
     * @param string $table_name
     * @param array $params where to select
     * @param array $arguments like: Limit => 0,5 OR OrderBy => row_name,ASC
     * @return object||array returns object or objects from db
     */
    public function Load($model, array $params = array(), array $arguments = array()) 
    {
        $model = "Janpa\\App\\Model\\" . $model;
        $object = new $model;
        $columns = $this->qb->ShowColumns($object->table_name);
        $this->qb->Select($object->table_name, $columns);
        $this->qb->Where($params);
        foreach($arguments as $argument => $value) {
            if($argument == "Limit") 
                $this->qb->Limit(explode(",", $value)[0], explode(",", $value)[1]);
            else if($argument == "OrderBy") 
                $this->qb->OrderBy(explode(",", $value)[0], explode(",", $value)[1]);
        }
        $values = $this->qb->Execute();
        if(count($values) == 1) {
            foreach ($values[0] as $column => $value) {
                $function_name = "Set" . ucfirst($column);
                if(method_exists($object, $function_name))
                    call_user_func(array($object, $function_name), $value);
                else {
                    ErrorHandler::error("Model function error!",
                    "Requested model '" . get_class($object) . "' doesn't have prop function '$function_name'
                    in " . $_SERVER["DOCUMENT_ROOT"] . "/app/model/" . get_class($object) . ".php" , 400);
                }
            }
            return $object;
        } else {
            $objects = array();
            foreach ($values as $fetched_object) {
                $object = new $model;
                foreach ($fetched_object as $column => $value) {
                    $function_name = "Set" . ucfirst($column);
                    if(method_exists($object, $function_name))
                        call_user_func(array($object, $function_name), $value);
                    else {
                        ErrorHandler::error("Model function error!",
                        "Requested model '" . get_class($object) . "' doesn't have prop function '$function_name'
                        in " . $_SERVER["DOCUMENT_ROOT"] . "/app/model/" . get_class($object) . ".php" , 400);
                    }
                }
                array_push($objects, $object);
            }
            return $objects;
        }
    }

    /**
     * Push model instance to database
     * @param object $object class instance to push
     * @return int insert id
     */
    public function Push($object) 
    {
        $props = $object->GetProperties();
        $table_name = explode("\\", get_class($object));
        $table_name = end($table_name) . "s";
        if(isset($props["id"])) 
            unset($props["id"]);
        if($this->qb->Exists($table_name, array("id" => $object->GetId()))) {
            $this->qb->Update($table_name, $props);
            $this->qb->Where(array("id" => $object->GetId()));
        }
        else
            $this->qb->Insert($table_name, $props);
        $this->qb->Execute();   
        return $this->qb->InsertId();
    }

    /**
     * Push models conained in array / made for multiple entity pushing
     * @param array $objects array containing entity objects
     * @return bool success
     */
    public function PushAll($objects = array()) 
    {
        $table_name = explode("\\", get_class($objects[0]));
        $table_name = end($table_name) . "s";
        foreach($objects as $object) {
            $props = $object->GetProperties();
            if($this->qb->Exists($table_name, array("id" => $object->GetId())))
                $this->qb->Update($table_name, $props);
            else
                $this->qb->Insert($table_name, $props);
            $this->qb->Execute();   
        }
        return true;
    }
}