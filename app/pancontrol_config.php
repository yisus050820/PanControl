<?php

define('DS', DIRECTORY_SEPARATOR);
define('PC_ROOT', __DIR__ . DS);

define('IS_LOCAL', in_array($_SERVER['REMOTE_ADDR'],['127.0.0.1','::1']) ? true : false );
define('PC_PORT', IS_LOCAL ? '80' : 'REMOTE PORT');
define('PC_URL', IS_LOCAL ? '127.0.0.1:'. PC_PORT . DS : 'REMOTE URL');

define('PC_DB_HOST', IS_LOCAL ? 'localhost' : 'REMOTE HOST');
define('PC_DB_USER', IS_LOCAL ? 'root' : 'REMOTE USER');
define('PC_DB_PASS', IS_LOCAL ? '' : 'REMOTE PASSWORD');
define('PC_DB_NAME', IS_LOCAL ? 'pancontrol' : 'REMOTE DATA BASE NAME');

define('PC_CLASSES'        , PC_ROOT . 'classes' . DS);
define('PC_CONTROLLERS'    , PC_ROOT . 'controllers' . DS);
define('PC_MODELS'         , PC_ROOT . 'models' . DS);
define('PC_RESOURCES'      , PC_ROOT . 'resources' . DS);
define('PC_ASSETS'         , '../PanControl/public/');
define('PC_CSS'            , PC_ASSETS . 'css/');
define('PC_JS'             , PC_ASSETS . 'js/');
define('PC_LAYOUTS'        , PC_RESOURCES . 'pancontrol_layouts' .DS);
define('PC_VIEWS'          , PC_RESOURCES . 'pancontrol_views' . DS);
define('PC_FUNCTIONS'      , PC_RESOURCES . 'functions' .DS);
