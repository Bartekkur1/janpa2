<?php

class QueryBuilder
{

    public $query, $mysqli, $config;

    /**
     * Mysql connection
     */
    function __construct()
    {
        $this->config = parse_ini_file("app/config.ini");
        if(!$this->mysqli = new mysqli("localhost", $this->config["login"], $this->config["password"], $this->config["dbname"]))
            die("xd");
        if ($this->mysqli->connect_errno) {
            ErrorHandler::ThrowNew("Database problem!", $this->mysqli->connect_error , 500);
        }
        $this->mysqli->set_charset('utf8');
        $this->connected = true;
    }
    
    /**
     * Returns injection clean array
     * @param array $inputs
     * @return array $clean_inputs db safe
     */
    private function EscapeArray($inputs = array())
    {
        $clean_inputs = array();
        foreach($inputs as $name => $input) {
            $clean_inputs[$name] = $this->mysqli->real_escape_string($input);
        }
        return $clean_inputs;
    }

    /**
     * Executes object query
     * @return mixed $result from query
     */
    public function Execute()
    {
        $result = $this->mysqli->query($this->query);
        $this->query = "";
        if(is_bool($result)) {
            // returns bool
            return $result;
        } else if(is_object($result)) {
            // returns object array
            $objects = array();
            while($obj = $result->fetch_object()) {
                array_push($objects, $obj);
            }
            mysqli_free_result($result);
            return $objects;
        }
    }

    /**
     * Checks if record exists in db
     * @param string $table_name
     * @param array $values from where object is selected
     */
    public function Exists($table_name, $values = array())
    {
        $this->query .= "SELECT `id` FROM `$table_name` WHERE ";
        $valueIndx = 0;
        foreach ($values as $name => $value) {
            $valueIndx++;
            $this->query .= " `$name` = '$value' ";
            if ($valueIndx < count($values)) {
                $this->query .= " AND ";
            }
        }
        return $this->Execute();
    }

    /**
     * UPDATE `$table_name` SET '$key' = `$value`
     * Array structure is important here - array keys should be row names
     * @param string $table_name
     * @param array $values to update 
     */
    public function Update($table_name, $values = array())
    {
        $values = $this->EscapeArray($values);
        $this->query .= "UPDATE `$table_name` SET ";
        $valueIndx = 0;
        foreach($values as $name => $value) {
            $valueIndx++;
            $this->query .= "`$name` = '$value'";
            if ($valueIndx < count($values)) {
                $this->query .= ", ";
            }
        }
    }

    /**
     * @return int $InsertId from last insert query
     */
    public function InsertId() {
        return $this->mysqli->insert_id;
    }

    /**
     * INSERT INTO $table_name ($params) VALUES ($values)
     * Array structure is important here - array keys should be row names
     * @param string $table_name
     * @param array $values to insert
     */
    public function Insert($table_name, $values = array())
    {
        $values = $this->EscapeArray($values);
        $this->query .= "INSERT INTO `$table_name` (";
        $paramIndx = 0;
        foreach($values as $name => $value) {
            $paramIndx++;
            $this->query .= "`$name`";
            if ($paramIndx < count($values)) {
                $this->query .= ", ";
            }
        }
        $this->query .= ") VALUES (";
        $valueIndx = 0;
        foreach($values as $value) {
            $valueIndx++;
            $this->query .= "'$value'";
            if ($valueIndx < count($values)) {
                $this->query .= ", ";
            }
        }
        $this->query .= ")";
    }

    /**
     * ORDER BY `$row_name` $type  -  DESC or ASC
     * @param string $row_name to order
     * @param string $type in which order should be
     */
    public function OrderBy($row_name, $type)
    {
        $this->query .= " ORDER BY `$row_name` $type ";
    }

    /**
     * SELECT COUNT(`id`) as `count` FROM `$table_name`
     * @param string $table_name
     * @return int number of rows found
     */
    public function Count($table_name)
    {
        $this->query = "SELECT COUNT(`id`) as 'count' FROM `$table_name`";
        return $this->Execute();
    }

    /**
     * Function made for ORM - returns table column names
     * @param string $table_name
     * @return array $columns
     */
    public function ShowColumns($table_name) {
        $columns = array();
        $this->query = "SELECT column_name FROM information_schema.columns WHERE  table_name = '$table_name' AND table_schema = '" . $this->config["dbname"] . "'";
        foreach($this->Execute() as $column) {
            array_push($columns, $column->column_name);
        }
        return $columns;
    }

    /**
     * If values empty - SELECT * FROM $table_name
     * else SELECT $values FROM $table_name
     * @param string $table_name
     * @param array $values
     */
    public function Select($table_name, $values = array())
    {
        $values = $this->EscapeArray($values);
        $this->query .= "SELECT";
        if (!empty($values)) {
            $valuesIndx = 0;
            foreach ($values as $value) {
                $valuesIndx++;
                $this->query .= " `$value`";
                if ($valuesIndx < count($values)) {
                    $this->query .= ", ";
                }
            }
        } else {
            $this->query .= " * ";
        }
        $this->query .= " FROM `$table_name`";
    }

    /**
     * If params empty - WHERE 1
     * WHERE $key = $param AND ...
     * @param array $params
     */
    public function Where($params = array())
    {
        $params = $this->EscapeArray($params);
        $this->query .= " WHERE ";
        if (!empty($params)) {
            $paramIndx = 0;
            foreach ($params as $name => $param) {
                $paramIndx++;
                $this->query .= " `$name` = '$param' ";
                if ($paramIndx < count($params)) {
                    $this->query .= " AND ";
                }
            }
        } else {
            $this->query .= " 1";
        }
    }

    /**
     * LIMIT $start, $amount
     * @param int $start
     * @param int @amount
     */
    public function Limit($start, $amount)
    {
        $this->query .= " LIMIT $start, $amount";
    }

    /**
     * DELETE FROM $table_name
     * @param string $table_name
     */
    public function Delete($table_name)
    {
        $this->query .= "DELETE FROM `$table_name` ";
    }
}