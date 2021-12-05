<?php

    namespace App\core;

    class Request{
        public $verb;
        public $url_parameters;
        public $payload;
        public $content_type;
        public $accept;
        public $tokenAuth;
        public $auth;

        function __construct(){
            $this->verb = $_SERVER["REQUEST_METHOD"];

            $this->url_parameters = array();
            parse_str($_SERVER["QUERY_STRING"], $this->url_parameters);

            $this->accept = $_SERVER["HTTP_ACCEPT"];
            $this->auth = $this->get_auth();

            switch ($this->verb) {
                case "POST": $this->payload = $_POST; break;
                case "PATCH": parse_str(file_get_contents("php://input"), $this->payload); break;
                case "GET": $this->payload = $_GET; break;
                case "DELETE": parse_str(file_get_contents("php://input"), $this->payload); break;
            }
            //$this->content_type = $_SERVER["CONTENT_TYPE"];
            //$this->tokenAuth = preg_replace('/Bearer\s/', '', $_SERVER['HTTP_AUTHORIZATION']);
        }

        private function get_auth() {
            if (isset($this->url_parameters['key'])) {
                $key = $this->url_parameters['key'];
                unset($this->url_parameters['key']);
                return $key;
            }
            return false;
        }
    }
?>