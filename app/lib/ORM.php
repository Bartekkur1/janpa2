<?php

class ORM {

    private static $qb;

    static function Setup()
    {
        self::$qb = new QueryBuilder();
    }

    static function Load($table_name, array $params) {
        $columns = self::$qb->ShowColumns($table_name);
        self::$qb->Select($table_name, $columns);
        self::$qb->Where($params);
        return self::$qb->Execute();
    }

}