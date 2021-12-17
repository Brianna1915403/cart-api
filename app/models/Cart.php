<?php 
    namespace App\models;

    use PDO;

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

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        function getAllFromUser($userID){
            $query = "SELECT * FROM cart WHERE user_id = :user_ID";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["user_ID"=>$userID]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        function getByUserID($user_id){
            $query = "SELECT * FROM cart WHERE user_id = :user_id";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["user_id"=>$user_id]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        function insert($user_id, $item_id, $item_amount, $status, $client_id){
            $query = "INSERT INTO cart(user_id, item_ids, item_amounts, status, client_id)
                        VALUES(:user_id, :item_id, :item_amount, :status, :client_id)";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["user_id"=>$user_id, "item_id"=>$item_id, "item_amount"=>$item_amount, "status"=>$status, "client_id"=>$client_id]);

            return $stmt->fetch();
        }

        function update_contents($cart_id, $item_ids, $item_amounts) {
            $query = "UPDATE cart SET item_ids = :item_ids, item_amounts = :item_amounts WHERE cart_id = :cart_id";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["cart_id"=>$cart_id, "item_ids"=>$item_ids, "item_amounts"=>$item_amounts]);
        }

        function update_status($cart_id, $status){
            $query = "UPDATE cart SET status = :status WHERE cart_id = :cart_id";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["status"=>$status, "cart_id"=>$cart_id]);
        }

        function delete($cart_id){
            $query = "DELETE FROM cart WHERE cart_id = :cart_id";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["cart_id"=>$cart_id]);
        }
    }

?>