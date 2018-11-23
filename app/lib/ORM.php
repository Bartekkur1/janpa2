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
     */
    static function Delete($table_name, array $params) {
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
     * @param string $table_name
     * @param array params where to select
     */
    static function Load($table_name, array $params) {
        $object = new $table_name;
        $table_name .= "s";
        $columns = self::$qb->ShowColumns($table_name);
        self::$qb->Select($table_name, $columns);
        self::$qb->Where($params);
        $values = self::$qb->Execute()[0];
        foreach ($values as $column => $value) {
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
    }

    /**
     * 
     */
    static function Push($object) {
        $props = $object->GetProperties();
        var_dump($props);
        $table_name = get_class($object) . "s";
        self::$qb->Insert($table_name, $props);
        self::$qb->Execute();
        echo self::$qb->InsertId();
    }
}