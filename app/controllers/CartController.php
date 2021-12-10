<?php
    namespace App\controllers;

    class CartController extends \App\core\Controller {
        
        private $cart;

        function __construct() {$this->cart = new Cart();}
        
        function getAllItemsInCart($userID){
            return $this->cart->getAllItems($userID);
        }

        function addItemInCart($userID, $itemID, $itemAmount, status){
            return $this->cart->addItem($userID, $itemID, $itemAmount, status);
        }

        function updateItemAmountInCart($cartID, $itemAmount){
            return $this->cart->updateItemAmount($cartID, $itemAmount);
        }

        function updateStatus($cartID, $status){
            return $this->cart->updateStatus($cartID, $status);
        }
        
        function removeItemInCart($cartID, $itemID, $userID){
            return $this->cart->removeItem($cartID, $itemID, $userID);
        }

        function index() {
            echo "CartController";
            $this->view('index');
        }
    }        
?>