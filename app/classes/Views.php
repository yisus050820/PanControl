<?php    namespace app\classes;

    class Views {

        public static function render($view, $data = []){
            if(!is_array($data)) {
                throw new \Exception('El segundo parámetro debe ser un array');
            }
            
            $d = as_object($data);
            
            if(!file_exists(VIEWS . $view . '.view.php')) {
                throw new \Exception('La vista ' . $view . ' no existe');
            }
            
            require_once VIEWS . $view . '.view.php';
            exit();
        }

    }