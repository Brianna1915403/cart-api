<?php
    namespace App\controllers;

    class ItemController extends \App\core\Controller {
        
        function index() {
            echo "ItemController";
            $this->view('index');
        }
    }        
?>