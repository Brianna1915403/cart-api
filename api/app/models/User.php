<?php 
    namespace App\models;

    use PDO;

    class User extends \App\core\Model {

        // DB Fields: user_id | email | password | license_key

        public function __construct() { 
            parent::__construct();
        }

        function getByKey($license_key) {
            $query = "SELECT * FROM user WHERE license_key = :license_key";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["license_key"=>$license_key]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        function getByEmail($email) {
            $query = "SELECT * FROM user WHERE email = :email";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["email"=>$email]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        function insert($email, $password, $license_key) {
            $query = "INSERT INTO user(email, password, license_key) VALUES(:email, :password, :license_key)";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(["email"=>$email, "password"=>$password, "license_key"=>$license_key]);
        }

        function update($user_id, $email, $password) {
            if (!$password) {
                $query = "UPDATE user SET email = :email WHERE user_id = :user_id";
                $stmt = self::$connection->prepare($query);
                $stmt->execute(["user_id"=>$user_id, "email"=>$email]);
            } else {
                $query = "UPDATE user SET email = :email, password = :password  WHERE user_id = :user_id";
                $stmt = self::$connection->prepare($query);
                $stmt->execute(["user_id"=>$user_id, "email"=>$email, "password"=>$password]);
            }
        }

        //TODO: Delete method
        
    }

?>