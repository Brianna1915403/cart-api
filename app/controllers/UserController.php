<?php
    namespace App\controllers;

    use App\models\User;
    use App\core\Token;

    class UserController extends \App\core\Controller {
            
        private $user;

        function __construct() { $this->user = new User(); }

        function verify_auth($token) {
            $key = Token::encode($token, explode('.', $token)[0]);
            $user_data = $this->user->getByKey($key);
            return $user_data;
        }

        function insert($email, $password) {
            if (!$this->user->getByEmail($email)) {   
                $api_key = Token::get_api_key();
                $prefix = explode('.', $api_key)[0];
                $password = password_hash($password, PASSWORD_DEFAULT);

                $this->user->insert($email, $password, Token::encode($api_key, $prefix));
                $this->view('index', ['status' => http_response_code(), 'token' => $api_key]);
            } else {
                $this->view('errors/400');
            }
        }

    }        
?>