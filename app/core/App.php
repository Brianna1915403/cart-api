<?php
    namespace App\core;
    
    class App {
        protected $controller;
        protected $method = 'index';
        protected $params = [];

        private $request;

        public function __construct() {
            // header("Content-Type: application/json");
            
            $this->request = new Request();            
            $this->set_controller();

            if ($this->controller) {
                $this->controller = new $this->controller();
                $controller = get_class($this->controller);

                if ($controller == "App\\controllers\\CartController") {
                    echo "CartController";
                } else if ($controller == "App\\controllers\\ItemController") {
                    echo "ItemController";
                } else if ($controller == "App\\controllers\\UserController") {
                    echo "UserController";
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

        }

        function set_controller() {
            $keys = $this->request->url_parameters;
            // var_dump($keys);
            if (isset($keys['controller'])) {
                if (file_exists('app/controllers/' . ucfirst($keys['controller']) . 'Controller.php')) {
                    $this->controller = 'App\\controllers\\' . $keys['controller'] . 'Controller';
                } else {
                    include("app/views/errors/404.php");
                }
                unset($keys['controller']);
            }
        }
    }

?>