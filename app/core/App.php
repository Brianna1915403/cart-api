<?php
    namespace App\core;
    
    class App {
        protected $controller = 'App\\controllers\\APIController';
        protected $method = 'index';
        protected $params = [];

        public function __construct() {
            $url = $this->parseUrl();
            
            if (isset($url[0])) {
                if (file_exists('app/controllers/' . $url[0] . 'Controller.php')) {
                    $this->controller = 'App\\controllers\\' . $url[0] . 'Controller';
                }
                unset($url[0]);
            }

            $this->controller = new $this->controller();

            if (isset($url[1])) {
                if (method_exists($this->controller, $url[1])) {
                    $this->method = $url[1];
                }
                unset($url[1]);
            }

            $reflection = new \ReflectionObject($this->controller);
            $controllerAttributes = $reflection->getAttributes();
            $methodAttributes = $reflection->getMethod($this->method)->getAttributes();
            $filters = array_values(array_filter(array_merge($controllerAttributes, $methodAttributes)));
            foreach($filters as $filter) {
                $filter = $filter->newInstance();
                $filter->execute();
            }

            $this->params = $url? array_values($url) : [];

            call_user_func_array([$this->controller, $this->method], $this->params);
        }

        function parseUrl() {
            if(isset($_GET['url'])) {
                return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
            }
        }
    }

?>