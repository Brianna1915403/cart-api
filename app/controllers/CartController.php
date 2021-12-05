<?php
    namespace App\controllers;

    class CartController extends \App\core\Controller {
        
        function index() {
            echo "CartController";
            $this->view('index');
        }
    }        
?>