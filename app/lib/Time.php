<?php

class Time
{
    public $date_time;

    function __construct()
    {
        date_default_timezone_set("Europe/Warsaw");
        $this->date_time = new DateTime();
    }

    /**
     * @param string $order "Y-m-d"
     * @return string returns date in given order
     */
    public function get_date($order = "Y-m-d")
    {
        return $this->date_time->format($order);
    }

    /**
     * @return string
     */
    public function get_time()
    {
        return $this->date_time->format("H:i:s");
    }

    /**
     * @return string
     */
    public function get_datetime()
    {
        return $this->date_time->format("Y-m-d H:i:s");
    }

}