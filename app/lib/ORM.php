<?php

class ORM {

    private static $qb;

    /**
     * Seting up database connection on query builder
     */
    static function Setup()
    {
        self::$qb = new QueryBuilder();
    }
    
    /**
     * @param string $table_name 
     * @param array $params where to delete
     * @return bool success
     */
    static function Delete($table_name, array $params) 
    {
        $table_name .= "s";
        if(self::$qb->Exists($table_name, $params)) {
            self::$qb->Delete($table_name);
            self::$qb->Where($params);
            return self::$qb->Execute() ? true : false;    
        } else {
            return false;
        }
    }

    /**
     * Updates object from given object
     * REMEMBER TO SET ID!!
     * @param object $object
     * @return bool success
     */
    static function Update($object)
    {
        
    }

    /**
     * TODO arguments implementation
     * Returns object founded in db / leave array empty to load all
     * @param string $table_name
     * @param array $params where to select
     * @param array $arguments like: Limit => 0,5 OR OrderBy => row_name,ASC
     * @return object||array returns object or objects from db
     */
    static function Load($model, array $params = array(), array $arguments = array()) 
    {
        $table_name = $model . "s";
        $columns = self::$qb->ShowColumns($table_name);
        self::$qb->Select($table_name, $columns);
        self::$qb->Where($params);
        foreach($arguments as $argument => $value) {
            if($argument == "Limit") 
                self::$qb->Limit(explode(",", $value)[0], explode(",", $value)[1]);
            else if($argument == "OrderBy") 
                self::$qb->OrderBy(explode(",", $value)[0], explode(",", $value)[1]);
        }
        $values = self::$qb->Execute();
        if(count($values) == 1) {
            $object = new $model;
            foreach ($values[0] as $column => $value) {
                $function_name = "Set" . ucfirst($column);
                if(method_exists($object, $function_name))
                    call_user_func(array($object, $function_name), $value);
                else {
                    ErrorHandler::ThrowNew("Model function error!",
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
                        ErrorHandler::ThrowNew("Model function error!",
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
    static function Push($object) 
    {
        $props = $object->GetProperties();
        $table_name = get_class($object) . "s";
        self::$qb->Insert($table_name, $props);
        self::$qb->Execute();   
        return self::$qb->InsertId();
    }
}