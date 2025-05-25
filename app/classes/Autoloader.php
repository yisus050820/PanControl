<?php

namespace app\classes;

class Autoloader {
    public static function register(){
        spl_autoload_register([__CLASS__,'autoload']);
    }
    
    private static function autoload($class){
        // Convertir namespace separators a directory separators
        $path = str_replace('\\', DS, $class);
        
        // Construir rutas posibles
        $possibilities = [
            APP . $path . '.php',
            ROOT . $path . '.php'
        ];
        
        // Buscar el archivo en las posibles ubicaciones
        foreach ($possibilities as $file) {
            if (file_exists($file)) {
                require_once $file;
                return true;
            }
        }
        
        return false;
    }
}