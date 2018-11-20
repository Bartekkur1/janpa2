<?php

    class Loader {

        /**
         * @param $file string file name to require from public directory
         */
        public static function load_html($file) {
            if(!file_exists($_SERVER["DOCUMENT_ROOT"] . "/public//" . $file . ".php")) {
                echo "header '$file' not found";
            } else {
                require $_SERVER["DOCUMENT_ROOT"] .  "/public//" . $file . ".php";
            }
        }

        /**
         * @param $model_name string model name to include / don't use .php
         */
        public function load_model($model_name)
        {
            if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/app/model/$model_name.php")) {
                require_once $_SERVER["DOCUMENT_ROOT"] . "/app/model/$model_name.php";
            } else {
                echo "Model named : $model_name not found";
                die;
            }
            $this->$model_name = new $model_name;
        }

        /**
         * @param $lib_name string library name to include / don't use .php
         */
        public function load_lib($lib_name)
        {
            if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/app/lib/$lib_name.php")) {
                require $_SERVER["DOCUMENT_ROOT"] . "/app/lib/$lib_name.php";
            } else {
                echo "Library named : $lib_name not found";
                die;
            }
            $this->$lib_name = new $lib_name;
        }

        /**
         * Created for extreme cases
         */
        public function load_all_models()
        {
            foreach (glob($_SERVER['DOCUMENT_ROOT'] ."/app/model/*.php") as $model)
            {
                require_once($model);
                $model_name = basename($model, ".php");
                $this->$model_name = new $model_name;
            }
        }
    }