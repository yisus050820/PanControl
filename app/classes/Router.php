<?php 

    namespace app\classes;

    use app\controllers\ErrorController as ErrorController;

    class Router{
        private $uri = "";
        public function __construct(){}

        public function route(){
            $this->filterRequest();
            $controller = $this->getController();
            $method     = $this->getMethod();
            $params     = $this->getParams();
            // Instanciar el controlador dinámicamente
            if( class_exists( $controller ) ){
                $controller = new $controller();
            }else{
                $controller = new ErrorController();
                
                $controller->error404();
            }
            if( !method_exists($controller,$method)){
                $controller = new ErrorController();
                $controller->errorMNF();
            }
            $controller->$method($params); 
            return;
        }

        private function filterRequest(){
           $request = filter_input_array(INPUT_GET);
           if( isset($request['uri']) ){
            $this->uri = $request['uri'];
            $this->uri = rtrim($this->uri,'/');
            $this->uri = filter_var($this->uri,FILTER_SANITIZE_URL);
            $this->uri = explode('/',ucfirst(strtolower( $this->uri )));
            return;
           }
        }        private function getController(){
            $controller = 'Home';
            if( isset( $this->uri[0]) ){
                $controller = $this->uri[0];
                unset($this->uri[0]);
            }
            $controller = ucfirst( $controller );
            
            // Rutas de autenticación
            if( $controller == 'Session' ) $controller = "auth\\Session";
            if( $controller == 'Register' ) $controller = "auth\\Register";
            
            // Rutas de PanControl
            if( $controller == 'Pancontrol' ) {
                $subController = isset($this->uri[1]) ? ucfirst($this->uri[1]) : 'Dashboard';
                unset($this->uri[1]);
                $controller = "pancontrol\\{$subController}";
            }
            
            $controller = 'app\controllers\\' . $controller . 'Controller';
            return $controller;

        }

        private function getMethod(){
            $method = 'index';
            if( isset($this->uri[1]) ){
                $method = $this->uri[1];
                unset($this->uri[1]);
            }
            return $method;
        }

        private function getParams(){
            $params = [];
            if( !empty($this->uri) ){
                $params = $this->uri;
                $this->uri = "";
            }
            return $params;
        }
 
    }
