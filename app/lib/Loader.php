<?php

    class Loader {

        /**
         * DON'T FORGET FILE EXTENSION
         * @param $file string file name to require from public directory
         */
        public static function LoadPublic($file) {
            if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/public/" . $file)) {
                // require $_SERVER["DOCUMENT_ROOT"] .  "/public/" . $file;
                return $_SERVER["DOCUMENT_ROOT"] .  "/public/" . $file;
            } else {
                echo "header '$file' not found";
            }
        }

        /**
         * @param $lib_name string library name to include / don't use .php
         */
        public static function LoadLib($lib_name)
        {
            if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/app/lib/$lib_name.php")) {
                require $_SERVER["DOCUMENT_ROOT"] . "/app/lib/$lib_name.php";
            } else {
                echo "Library named : $lib_name not found";
                die;
            }
        }


    }