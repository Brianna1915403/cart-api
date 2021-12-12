<?php
    namespace App\core;

use App\controllers\UserController;

class App {
        protected $controller;
        protected $method = '';
        protected $params = [];

        private $request;

        public function __construct() {
            
            $this->request = new Request();   
            // var_dump($this->request->url_parameters);


            $this->set_controller();
            $this->set_method();
            $this->set_params();

            if ($this->controller) {
                $this->controller = new $this->controller();
                $controller = get_class($this->controller);

                if ($controller == "App\\controllers\\CartController") {
                    echo "CartController";
                } else if ($controller == "App\\controllers\\ItemController") {
                    switch ($this->request->verb) {
                        case "GET": 
                            $user = $this->verify_authentication();
                            if ($this->request->auth && $user) {  
                                if ($this->method) {
                                    $this->controller->get_one($user['user_id'], intval($this->method));
                                } else {
                                    $this->controller->get_all($user['user_id']);
                                }
                            }
                            break;
                        case "POST": // Create an item
                            if ($this->request->auth) {
                                $user = $this->verify_authentication();
                                if (isset($this->request->payload['item_name']) && 
                                    isset($this->request->payload['description']) &&
                                    isset($this->request->payload['price']) &&
                                    isset($this->request->payload['picture']) &&
                                    isset($this->request->payload['tag']) &&
                                    isset($this->request->payload['stock']) &&
                                    $user)
                                {
                                    $this->controller->insert(
                                        $user['user_id'],
                                        $this->request->payload['item_name'], 
                                        $this->request->payload['description'],
                                        $this->request->payload['price'],
                                        $this->request->payload['picture'],
                                        $this->request->payload['tag'],
                                        $this->request->payload['stock']
                                    );
                                } else {
                                    include("app/views/errors/400.php");
                                }
                            } else {
                                include("app/views/errors/400.php");
                            }
                            break;
                        case "PATCH": // Update a user's email and password
                            if ($this->request->auth && $this->verify_authentication()) {                                    
                                if (isset($this->request->payload['email']) && isset($this->request->payload['old_password']) && isset($this->request->payload['new_password'])) {
                                    $this->controller->update_password(
                                        $this->request->payload['email'], 
                                        $this->request->payload['old_password'], 
                                        $this->request->payload['new_password']
                                    );
                                } else if (isset($this->request->payload['old_email']) && isset($this->request->payload['new_email']) && isset($this->request->payload['password'])) {
                                    $this->controller->update_email(
                                        $this->request->payload['password'], 
                                        $this->request->payload['old_email'], 
                                        $this->request->payload['new_email']
                                    );
                                }else {
                                    include("app/views/errors/400.php");
                                }
                            } else {
                                include("app/views/errors/401.php");
                            }
                            break;
                        case "DELETE": echo "DELETE"; break;
                        default: include("app/views/errors/400.php"); break;
                    }
                } else if ($controller == "App\\controllers\\UserController") {                    
                    $this->user();             
                }
            }
        }

        function verify_authentication() {
            $user_controller = new UserController();
            return $user_controller->verify_auth($this->request->auth);
        }

        function set_controller() {
            $keys = $this->request->url_parameters;
            if (isset($keys['controller'])) {
                if (file_exists('app/controllers/' . ucfirst($keys['controller']) . 'Controller.php')) {
                    $this->controller = 'App\\controllers\\' . ucfirst($keys['controller']) . 'Controller';
                } else {
                    include("app/views/errors/404.php");
                }
                unset($this->request->url_parameters['controller']);
            }
        }

        function set_method() {
            $keys = $this->request->url_parameters;
            if (isset($keys['method'])) {
                $this->method = $keys['method'];
                unset($this->request->url_parameters['method']);
            }
        }

        function set_params() {
            $this->params = $this->request->url_parameters;
            unset($this->request->url_parameters);
        }

        function user() {
            if ($this->method == "") {
                switch ($this->request->verb) {
                    case "GET": 
                        if ($this->request->auth && $this->verify_authentication()) {  
                            $this->controller->get();
                        }
                        break;
                    case "POST": // Create a user
                        if (!$this->request->auth) {
                            if (isset($this->request->payload['email']) && isset($this->request->payload['password'])) {
                                $this->controller->insert(
                                    $this->request->payload['email'], 
                                    $this->request->payload['password']
                                );
                            } else {
                                include("app/views/errors/400.php");
                            }
                        } else {
                            include("app/views/errors/400.php");
                        }
                        break;
                    case "PATCH": // Update a user's email and password
                        if ($this->request->auth && $this->verify_authentication()) {                                    
                            if (isset($this->request->payload['email']) && isset($this->request->payload['old_password']) && isset($this->request->payload['new_password'])) {
                                $this->controller->update_password(
                                    $this->request->payload['email'], 
                                    $this->request->payload['old_password'], 
                                    $this->request->payload['new_password']
                                );
                            } else if (isset($this->request->payload['old_email']) && isset($this->request->payload['new_email']) && isset($this->request->payload['password'])) {
                                $this->controller->update_email(
                                    $this->request->payload['password'], 
                                    $this->request->payload['old_email'], 
                                    $this->request->payload['new_email']
                                );
                            }else {
                                include("app/views/errors/400.php");
                            }
                        } else {
                            include("app/views/errors/401.php");
                        }
                        break;
                    case "DELETE": echo "DELETE"; break;
                    default: include("app/views/errors/400.php"); break;
                }    
            } else {
                include("app/views/errors/404.php");
            }  
        }
    }

?>