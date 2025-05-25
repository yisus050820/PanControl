<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__) . DS);
define('APP', ROOT . 'app' . DS);
define('PUBLIC_PATH', ROOT . 'public' . DS);

define('IS_LOCAL', in_array($_SERVER['REMOTE_ADDR'],['127.0.0.1','::1']) ? true : false );
define('PORT', IS_LOCAL ? '80' : 'REMOTE PORT');
define('BASE_URL', '/PI2/');
define('URL', IS_LOCAL ? 'http://localhost' . BASE_URL : 'REMOTE URL');

// Configuración de base de datos
define('DB_HOST', IS_LOCAL ? 'localhost' : 'REMOTE HOST');
define('DB_USER', IS_LOCAL ? 'root' : 'REMOTE USER');
define('DB_PASS', IS_LOCAL ? '' : 'REMOTE PASSWORD');
define('DB_NAME', IS_LOCAL ? 'pancontrol' : 'REMOTE DATA BASE NAME');

// Rutas del sistema
define('CLASSES',        APP . 'classes' . DS);
define('CONTROLLERS',    APP . 'controllers' . DS);
define('MODELS',         APP . 'models' . DS);
define('VIEWS',          APP . 'resources' . DS . 'views' . DS);
define('FUNCTIONS',      APP . 'resources' . DS . 'functions' . DS);
define('LAYOUTS',        APP . 'resources' . DS . 'layouts' . DS);

// Assets y recursos web
define('ASSETS',         URL . 'assets/');
define('CSS',           ASSETS . 'css/');
define('JS',            ASSETS . 'js/');
define('IMG',           ASSETS . 'images/');

// Configuración del sistema
define('HASH_COST',     10);
define('APP_NAME',      'PanControl');
define('APP_VERSION',   '1.0.0');
define('TIMEZONE',      'America/Mexico_City');