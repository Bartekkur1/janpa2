<?php

    class Loader {

        /**
         * DON'T FORGET FILE EXTENSION
         * @param $file string file name to require from public directory
         */
        public static function LoadPublic($file) {
            if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/public/" . $file)) {
                require $_SERVER["DOCUMENT_ROOT"] .  "/public/" . $file;
            } else {
                ErrorHandler::ThrowNew("Public file not found!",
                "Requested public file '$lib_name' could not be found " . debug_backtrace()[0]["file"] .
                " at line " . debug_backtrace()[0]["line"] . "" , 400);   
            }
        }

        /**
         * @param $lib_name string library name to include / don't use .php
         */
        public function LoadLib($lib_name)
        {
            if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/app/lib/$lib_name.php"))
                $this->$lib_name = new $lib_name;
            else 
                ErrorHandler::ThrowNew("Library not found!",
                "Requested library '$lib_name' could not be found " . debug_backtrace()[0]["file"] .
                " at line " . debug_backtrace()[0]["line"] . "" , 400);   
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

        /**
         * @param $model_name string model name to include / don't use .php
         */
        public function load_model($model_name)
        {   
            if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/app/model/$model_name.php"))
                require_once $_SERVER["DOCUMENT_ROOT"] . "/app/model/$model_name.php";
            else
                ErrorHandler::ThrowNew("Model not found!",
                "Requested model '$model_name' could not be found " . debug_backtrace()[0]["file"] .
                " at line " . debug_backtrace()[0]["line"] . "" , 400);            
            $this->$model_name = new $model_name;
        }
    }