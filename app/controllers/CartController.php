<?php
    namespace App\controllers;

    use App\models\Cart;

    class CartController extends \App\core\Controller {
        
        private $cart;

        function __construct() {$this->cart = new Cart();}
        
        function getAllFromUser($userID){
            return $this->cart->getAllFromUser($userID);
        }

        function insert($userID, $itemID, $itemAmount, $status){
            return $this->cart->insert($userID, $itemID, $itemAmount, $status);
        }

        function updateItemAmountInCart($cartID, $itemAmount){
            return $this->cart->updateItemAmount($cartID, $itemAmount);
        }

        function updateStatus($cartID, $status){
            return $this->cart->updateStatus($cartID, $status);
        }
        
        function delete($cartID, $itemID, $userID){
            return $this->cart->delete($cartID, $itemID, $userID);
        }

        function index() {
            echo "CartController";
            $this->view('index');
        }
    }        
?>