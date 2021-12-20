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

        function getByIndex($user_id, $item_index) {
            $query = "SELECT * FROM item WHERE item_index = :item_index AND user_id = :user_id";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["item_index"=>$item_index, "user_id"=>$user_id]);

            return $stmt->fetch();
        }

        function insert($user_id, $item_index, $name, $desc, $price, $picture, $tag, $stock) {
            $query = "INSERT INTO item(user_id, item_index, item_name, description, price, picture, tag, stock) VALUES(:user_id, :item_index, :item_name, :description, :price, :picture, :tag, :stock)";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["user_id" => $user_id, "item_index" => $item_index, "item_name" => $name, "description" => $desc, "price" => $price, "picture" => $picture, "tag" => $tag, "stock" => $stock]);
        }

        // --- UPDATE START ---

        function update_item_name($item_id, $item_name) {
            $query = "UPDATE item SET item_name = :item_name WHERE item_id = :item_id";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["item_id" => $item_id, "item_name" => $item_name]);
        }

        function update_description($item_id, $description) {
            $query = "UPDATE item SET description = :description WHERE item_id = :item_id";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["item_id" => $item_id, "description" => $description]);
        }

        function update_price($item_id, $price) {
            $query = "UPDATE item SET price = :price WHERE item_id = :item_id";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["item_id" => $item_id, "price" => $price]);
        }

        function update_picture($item_id, $picture) {
            $query = "UPDATE item SET picture = :picture WHERE item_id = :item_id";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["item_id" => $item_id, "picture" => $picture]);
        }

        function update_tag($item_id, $tag) {
            $query = "UPDATE item SET tag = :tag WHERE item_id = :item_id";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["item_id" => $item_id, "tag" => $tag]);
        }

        function update_stock($item_id, $stock) {
            $query = "UPDATE item SET stock = :stock WHERE item_id = :item_id";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["item_id" => $item_id, "stock" => $stock]);
        }

        // --- UPDATE END ---

        function delete($item_id) {
            $query = "DELETE FROM item WHERE item_id = :item_id";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["item_id" => $item_id]);
        }
    }

?>