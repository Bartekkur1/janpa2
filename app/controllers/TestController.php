<?php

use Janpa\App\Lib\Controller as Controller;

class TestController extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        echo "Hi, Im test controller. I like testing.";
    }
}