<?php
    namespace App\controllers;

    class APIController extends \App\core\Controller {
        
        function index() {
            $this->view('index');
        }
    }        
?>