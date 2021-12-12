<?php 
    namespace App\models;

    use PDO;

    class Item extends \App\core\Model {

        // DB Fields: item_id | user_id | item_name | description | price | picture | tag | stock

        public function __construct() { 
            parent::__construct();
        }

        // Returns an indexed array as opposed to a named array
        function getByUserID($user_id) {
            $query = "SELECT * FROM item WHERE user_id = :user_id";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["user_id"=>$user_id]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        function insert($user_id, $name, $desc, $price, $picture, $tag, $stock) {
            $query = "INSERT INTO item(user_id, item_name, description, price, picture, tag, stock) VALUES(:user_id, :item_name, :description, :price, :picture, :tag, :stock)";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["user_id" => $user_id, "item_name" => $name, "description" => $desc, "price" => $price, "picture" => $picture, "tag" => $tag, "stock" => $stock]);
        }

        // function update($client_id, $email, $password) {
        //     if (!$password) {
        //         $query = "UPDATE user SET email = :email WHERE client_ID = :client_id";
        //         $stmt = self::$connection->prepare($query);
        //         $stmt->execute(["client_id"=>$client_id, "email"=>$email]);
        //     } else {
        //         $query = "UPDATE user SET email = :email, password = :password  WHERE client_ID = :client_id";
        //         $stmt = self::$connection->prepare($query);
        //         $stmt->execute(["client_id"=>$client_id, "email"=>$email, "password"=>$password]);
        //     }
        // }

        //TODO: Delete method
        
    }

?>