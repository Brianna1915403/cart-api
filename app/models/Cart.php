<?php 
    namespace App\models;

    class Cart extends \App\core\Model {

        public function __construct() { 
            parent::__construct();
        }

        function getByID($id) {
            $query = "SELECT * FROM cart WHERE cart_id = :id";
            $stmt = $this->db_connection->prepare($query);
            $stmt->execute(["id"=>$id]);

            return $stmt->fetch();
        }

        function getAll() {
            $query = "SELECT * FROM cart";
            $stmt = self::$connection->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
    }

?>