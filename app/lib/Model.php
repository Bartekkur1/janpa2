<?php

class Model
{
    /**
     * @return array $props containg
     */
    public function GetProperties() {
        $props = array();
        foreach ($this as $key => $value) {
            if(isset($value))
                $props[$key] = $value;
        }
        return $props;
     }
}

