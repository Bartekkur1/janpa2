<?php

    class TestController extends Controller {

        private $model;

        function __construct() {

        }

        public function index() {
            echo "Hi, Im test controller. I like testing.";
        }
    }