<?php
    namespace App\controllers;

    class UserController extends \App\core\Controller {
        
        function index() {
            echo "UserController";
            $this->view('index');
        }

        //TODO: When a user is created use password_hash on the api key too
    }        
?>