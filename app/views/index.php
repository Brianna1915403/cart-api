<?php 
    spl_autoload_register('autoload');

    function autoload($class_name) {
        if (preg_match('/[a-zA-Z]+Controller$/', $class_name)) {
            require_once('../controllers/'.$class_name.'.php');
            return true;
        }
    }

    $url_params = [];
    parse_str($_SERVER['QUERY_STRING'], $url_params);
    var_dump($url_params);
?>