<?php
    namespace App\core;
    
    class Controller {
        
        protected function view($name, $data = null) {
            if (file_exists('app/views/' . $name . '.php')) {                
                include('app/views/' . $name . '.php');
            } else {
                echo $name . ' not found.';
            }
        }
    }
?>