<?php

namespace App\core;

    class Response{
        public $statusCode;
        public $headers;
        public $payload;

        function __construct(){
            $this->statusCode = http_response_code();
        }
    }
?>