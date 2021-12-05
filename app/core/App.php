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

            if ($this->controller) {
                $this->controller = new $this->controller();
                $controller = get_class($this->controller);

                if ($controller == "App\\controllers\\CartController") {
                    echo "CartController";
                } else if ($controller == "App\\controllers\\ItemController") {
                    echo "ItemController";
                } else if ($controller == "App\\controllers\\UserController") {                    
                    if ($this->method == "") {
                        switch ($this->request->verb) {
                            case "POST": // Create a user
                                if (!$this->request->auth) {
                                    if (isset($this->request->payload['email']) && isset($this->request->payload['password'])) {
                                        $this->controller->insert($this->request->payload['email'], $this->request->payload['password']);
                                    } else {
                                        include("app/views/errors/400.php");
                                    }
                                } else {
                                    include("app/views/errors/400.php");
                                }
                                break;
                            case "PATCH": // Update a user
                                if ($this->request->auth && $this->verify_authentication()) {
                                    // TODO: Upon changing either password or email check if credentials match
                                    // TODO: Need to figure out if user is trying to change email or password
                                    echo "Continue w/ PATCH";
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
            // var_dump($keys);

            // if (isset($url[1])) {
            //     if (method_exists($this->controller, $url[1])) {
            //         $this->method = $url[1];
            //     }
            //     unset($url[1]);
            // }

            // $this->params = $url? array_values($url) : [];

            // call_user_func_array([$this->controller, $this->method], $this->params);
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

        }
    }

?>