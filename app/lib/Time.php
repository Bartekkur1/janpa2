<?php

namespace Janpa\App\Lib;

class Time
{
    public $date_time;

    function __construct()
    {
        date_default_timezone_set("Europe/Warsaw");
        $this->date_time = new \DateTime();
    }

    /**
     * @param string $order "Y-m-d"
     * @return string returns date in given order
     */
    public function GetDate($order = "Y-m-d")
    {
        return $this->date_time->format($order);
    }

    /**
     * time now H:i:s
     * @return string 
     */
    public function GetTime()
    {
        return $this->date_time->format("H:i:s");
    }

    /**
     * time now Y-m-d H:i:s
     * @return string
     */
    public function GetDateTime()
    {
        return $this->date_time->format("Y-m-d H:i:s");
    }

}