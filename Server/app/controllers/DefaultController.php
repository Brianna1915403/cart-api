<?php
    namespace App\controllers;

    class DefaultController extends \App\core\Controller {
        
        function index() {
            $this->view('index');
        }
    }        
?>