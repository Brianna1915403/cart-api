<?php 
    namespace App\models;

    class Cart extends \App\core\Model {

        public function __construct() { 
            parent::__construct();
        }

        function getByID($id) {
            $query = "SELECT * FROM cart WHERE cart_id = :id";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["id"=>$id]);

            return $stmt->fetch();
        }

        function getAll() {
            $query = "SELECT * FROM cart";
            $stmt = self::$connection->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        function getAllFromUser($userID){
            $query = "SELECT * FROM cart WHERE user_id = :user_ID";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["user_ID"=>$userID]);

            return $stmt->fetch();
        }

        function insert($userID, $itemID, $itemAmount, $status){
            $query = "INSERT INTO cart(user_id, item_id, item_amount, status)
                        VALUES(:user_ID, :item_ID, :item_amount, :status)";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["user_ID"=>$userID, "item_ID"=>$itemID, "item_amount"=>$itemAmount, "status"=>$status]);

            return $stmt->fetch();
        }

        function updateItemAmount($cartID, $itemAmount){
            $query = "UPDATE cart SET item_amount = :item_amount WHERE cart_id = :cart_id";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["item_amount"=>$itemAmount, "cart_id"=>$cartID]);

            return $stmt->fetch();
        }

        function updateStatus($cartID, $status){
            $query = "UPDATE cart SET status = :status WHERE cart_id = :cart_id";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["status"=>$status, "cart_id"=>$cartID]);
            
            return $stmt->fetch();
        }

        function delete($cartID, $itemID, $userID){
            $query = "DELETE FROM cart WHERE cart_id = :cart_id, item_id = :item_id, user_id = :user_id";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["cart_id"=>$cartID, "item_id"=>$itemID, "user_id"=>$userID]);

            return $stmt->fetch();
        }
    }

?>