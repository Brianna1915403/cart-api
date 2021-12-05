<?php
    namespace App\core;
    
    class Model {        
        protected static $connection;

        public function __construct() {
            $username = 'root';
            $password = '';
            $host = 'localhost';
            $database = 'web_service_project';
            // PDO : PHP Data Object - to connect to DB
            self::$connection = new \PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
            self::$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
    }
?>