<?php

class ORM {

    private static $qb;

    static function Setup()
    {
        self::$qb = new QueryBuilder();
    }

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
    }
}