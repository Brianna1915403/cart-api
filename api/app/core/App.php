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

            $this->set_controller();
            $this->set_method();
            $this->set_params();

            if ($this->controller) {
                $this->controller = new $this->controller();
                $controller = get_class($this->controller);

                if ($controller == "App\\controllers\\CartController") {
                    $this->cart();
                } else if ($controller == "App\\controllers\\ItemController") {
                    $this->item();
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
            $user = $this->verify_authentication();
            if ($this->method == "") {
                switch ($this->request->verb) {
                    case "GET": 
                        if ($this->request->auth && $user) {  
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
                        if ($this->request->auth && $user) {                                    
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
                    case "DELETE": echo "DELETE"; break; // TODO: Delete everything related to the user
                    default: include("app/views/errors/400.php"); break;
                }    
            } else {
                include("app/views/errors/404.php");
            }  
        }

        function item() {
            $user = $this->verify_authentication();
            switch ($this->request->verb) {
                case "GET": 
                    if ($this->request->auth && $user) { 
                        if (($this->method != '' || !is_null($this->method))) {
                            if (is_numeric($this->method)) {
                                $this->controller->get_one($user['user_id'], intval($this->method));
                            } else {
                                include("app/views/errors/404.php");
                            }
                        } else {
                            $this->controller->get_all($user['user_id']);
                        }
                    } else {
                        include("app/views/errors/401.php");
                    }
                    break;
                case "POST": // Create an item
                    if ($this->request->auth) {
                        // Do I really need to check if all the fields are set? Yes but in the method call itself
                        if (isset($this->request->payload['item_name']) && $user) {
                            $this->controller->insert(
                                $user['user_id'],
                                $this->request->payload['item_name'], 
                                isset($this->request->payload['description']) ? $this->request->payload['description'] : null,
                                isset($this->request->payload['price']) ? $this->request->payload['price'] : 0.00,
                                isset($this->request->payload['picture']) ? $this->request->payload['picture'] : null,
                                isset($this->request->payload['tag']) ? $this->request->payload['tag'] : null,
                                isset($this->request->payload['stock']) ? $this->request->payload['stock'] : 0,
                            );
                        } else {
                            include("app/views/errors/400.php");
                        }
                    } else {
                        include("app/views/errors/401.php");
                    }
                    break;
                case "PATCH": 
                    // Not all fields are required nor do new/old_field exist
                    // Overlapp the new version over the old one, any items that are blank or empty
                    //  just stay as they were.     
                    if ($this->request->auth && $user) {
                        if (($this->method != '' || !is_null($this->method))) {
                            if (is_numeric($this->method)) {
                                $this->controller->update(
                                    $user['user_id'],
                                    intval($this->method),
                                    isset($this->request->payload['item_name']) ? $this->request->payload['item_name'] : null, 
                                    isset($this->request->payload['description']) ? $this->request->payload['description'] : null,
                                    isset($this->request->payload['price']) ? $this->request->payload['price'] : null,
                                    isset($this->request->payload['picture']) ? $this->request->payload['picture'] : null,
                                    isset($this->request->payload['tag']) ? $this->request->payload['tag'] : null,
                                    isset($this->request->payload['stock']) ? $this->request->payload['stock'] : null,
                                );
                            } else {
                                include("app/views/errors/400.php");
                            }
                        } else {
                            include("app/views/errors/400.php");
                        }
                    } else {
                        include("app/views/errors/401.php");
                    }            
                    break;
                case "DELETE": 
                    // Should the item id be replaces (i.e., if item #3 is deleted #4 will take it's place)? Yes?
                    if ($this->request->auth && $user) { 
                        if (($this->method != '' && !is_null($this->method))) {
                            $this->controller->delete($user['user_id'], intval($this->method));                                    
                        } else {
                            include("app/views/errors/400.php");                                    
                        }
                    } else {
                        include("app/views/errors/401.php"); 
                    }
                    break;
                default: include("app/views/errors/400.php"); break;
            }
        }

        function cart() {
            $user = $this->verify_authentication();
            switch ($this->request->verb){
                case "GET": // Get the items in the user cart
                    if($this->request->auth && $user){
                        if (($this->method != '' || !is_null($this->method))) {
                            if (is_numeric($this->method)) {
                                $this->controller->get_one($user['user_id'], intval($this->method));
                            } else {
                                include("app/views/errors/404.php");
                            }
                        } else {
                            $this->controller->get_all($user['user_id']);
                        }
                    } else {
                        include("app/views/errors/401.php");
                    }
                    break;
                case "POST":// Add an item to the user cart
                    if($this->request->auth && $user){
                        if (isset($this->request->payload['item_id']) &&
                            isset($this->request->payload['amount']) &&
                            isset($this->request->payload['status']) &&
                            $user) {
                            $this->controller->insert(
                                $user['user_id'],
                                $this->request->payload['item_id'],
                                $this->request->payload['amount'],
                                $this->request->payload['status'],
                                $this->request->payload['client_id'],
                            );
                        } else {
                            include("app/views/errors/400.php");
                        }
                    } else {
                        include("app/views/errors/400.php");
                    }
                    break;
                case "PATCH":// update the item info in the cart
                    if($this->request->auth && $user){
                        if((!is_null($this->method) || $this->method != '') && is_numeric($this->method)) {
                            if (isset($this->request->payload['item_id']) && 
                                isset($this->request->payload['amount'])) {
                                $this->controller->update_contents(
                                    $user['user_id'],
                                    intval($this->method),
                                    $this->request->payload['item_id'],
                                    $this->request->payload['amount']
                                );
                            } else if (isset($this->request->payload['status'])) {
                                $this->controller->update_status(
                                    $user['user_id'],
                                    intval($this->method),
                                    $this->request->payload['status']
                                );
                            }
                        } else {  
                            include("app/views/errors/400.php");
                        }
                    }else{
                        include("app/views/errors/400.php");
                    }
                    break;
                case "DELETE": // delete a cart 
                    if($this->request->auth && $user) {
                        if((!is_null($this->method) || $this->method != '') && is_numeric($this->method)) {
                            $this->controller->delete($user['user_id'], intval($this->method));
                        }
                    }else{
                        include("app/views/errors/400.php");
                    }
                    break;
                default: include("app/views/errors/400.php");
            }
        }
    }

?>