<?php

class Model
{
    
    public function GetProperties() {
        $props = array();
        foreach ($this as $key => $value) {
            if(isset($value))
                $props[$key] = $value;
        }
        return $props;
     }
}

