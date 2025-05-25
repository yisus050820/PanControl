<?php 

    namespace app\classes;

    use app\controllers\ErrorController as ErrorController;

    class Router {
        private $uri = [];
        private $controller;
        private $method;
        private $params;

        public function __construct() {
            $this->filterURI();
        }

        public function route() {
            $this->controller = $this->getController();
            $this->method = $this->getMethod();
            $this->params = $this->getParams();

            if (!class_exists($this->controller)) {
                $controller = new ErrorController();
                $controller->error404();
                return;
            }

            $controller = new $this->controller();
            if (!method_exists($controller, $this->method)) {
                $controller = new ErrorController();
                $controller->errorMNF();
                return;
            }

            call_user_func_array([$controller, $this->method], [$this->params]);
        }        private function filterURI() {
            if (!isset($_SERVER['REQUEST_URI'])) {
                return;
            }

            $uri = $_SERVER['REQUEST_URI'];
            // Remover el BASE_URL y public/ de la URI
            $uri = str_replace(BASE_URL . 'public/', '', $uri);
            $uri = rtrim($uri, '/');
            $uri = filter_var($uri, FILTER_SANITIZE_URL);
            $this->uri = explode('/', $uri);
        }

        private function getController() {
            if (empty($this->uri[0])) {
                return 'app\\controllers\\HomeController';
            }

            $controller = ucfirst(strtolower($this->uri[0]));
            
            // Manejo especial para controladores de autenticación
            if (in_array($controller, ['Session', 'Register'])) {
                return 'app\\controllers\\auth\\' . $controller . 'Controller';
            }

            return 'app\\controllers\\' . $controller . 'Controller';
        }

        private function getMethod() {
            if (empty($this->uri[1])) {
                return 'index';
            }
            return strtolower($this->uri[1]);
        }

        private function getParams() {
            if (count($this->uri) <= 2) {
                return [];
            }
            return array_slice($this->uri, 2);
        }
 
    }
