<?php 

    class Token {
        private $header;
        private $payload;
        private $signature;

        function __construct() {}

        // Unique id (since the uniqid method does not always produce unique ids)
        static function get_uniqid($lenght = 13) {
            $bytes = random_bytes(ceil($lenght / 2));

            return substr(bin2hex($bytes), 0, $lenght);
        }

        function get_api_key() {
            // This hash doesn't need to be super secure since it doesn't have any sensitive data 
            // so size is the priority. The api key shouldn't be too long, md5 has a length of 32 
            // so the final key is 43 characters long.
            $hash = hash("md5", Token::get_uniqid()); 
            $base64 = base64_encode($hash);
            $urlsafe = str_replace(['+', '/', '='], ['-', '_', ''], $base64);

            return $urlsafe;
        }

        function encode($client_id, $license_number, $expiration_date) {
            $this->header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
            $this->payload = json_encode(['client_id' => $client_id, "exp" => $expiration_date]);
            $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($this->header));
            $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($this->payload));
            $this->signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $license_number, true);
            $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($this->signature));
            $token = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
            
            return $token;
        }

        function decode($token) {
            if (preg_match('/^[\w\-]+[\.][\w\-]+[\.][\w\-]+$/', $token)) {
                return json_decode(base64_decode((str_replace(['-', '_', ''], ['+', '/', '='], str_replace(['-', '_', ''], ['+', '/', '='], explode('.', $token)[1])))), true);
            } else {
                return null;
            }           
        }
    }
    
?>