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

        function verify_email($email) {
            return preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix', $email);
        }

        function insert($email, $password) {
            if ($this->verify_email(strtolower($email)) && !$this->user->getByEmail(strtolower($email))) {   
                $api_key = Token::get_api_key();
                $prefix = explode('.', $api_key)[0];
                $password = password_hash($password, PASSWORD_DEFAULT);

                $this->user->insert(strtolower($email), $password, Token::encode($api_key, $prefix));
                $this->view('index', ['status' => http_response_code(201), 'token' => $api_key]);
            } else {
                $this->view('errors/400');
            }
        }

        function update_password($email, $old_password, $new_password) {
            $user_data = $this->user->getByEmail(strtolower($email));
            if ($user_data && password_verify($old_password, $user_data['password'])) {
                $password = password_hash($new_password, PASSWORD_DEFAULT);

                $this->user->update($user_data['client_ID'],strtolower($email), $password);
                $this->view('index', ['status' => http_response_code()]);
            } else {
                $this->view('errors/401');
            }
        }

        function update_email($password, $old_email, $new_email) {
            $user_data = $this->user->getByEmail(strtolower($old_email));
            if ($user_data && password_verify($password, $user_data['password'])) {
                if ($this->verify_email(strtolower($new_email)) && !$this->user->getByEmail(strtolower($new_email))) {
                    $this->user->update($user_data['client_ID'], strtolower($new_email), null);
                    
                    $this->view('index', ['status' => http_response_code()]);
                } else {
                    $this->view('errors/400');
                }
            } else {
                $this->view('errors/401');
            }
        }

        // TODO: Need other classes to fully delete a user
        function delete() {

        }

    }        
?>