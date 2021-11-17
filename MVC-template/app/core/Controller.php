<?php
    namespace App\core;
    class Controller {
        protected function view($name, $data = null) {
            // Checks that the file exists
            if (file_exists('app/views/' . $name . '.php')) {
                // Includes the file
                include('app/views/' . $name . '.php');
            } else {
                echo $name . ' not found.';
            }
        }
    }
?>