<?php
    class Request{
        public $verb;
        public $url_parameters;
        public $payload;
        public $content_type;
        public $accept;
        public $tokenAuth;

        function __construct(){
            $this->verb = $_SERVER["REQUEST_METHOD"];

            $this->url_parameters = array();
            parse_str($_SERVER["QUERY_STRING"], $this->url_parameters);

            $this->accept = $_SERVER["HTTP_ACCEPT"];

            $this->content_type = $_SERVER["CONTENT_TYPE"];
            //$this->tokenAuth = preg_replace('/Bearer\s/', '', $_SERVER['HTTP_AUTHORIZATION']);
        }
    }// end Request Class
?>