<?php 
    spl_autoload_register(function($class) {
        $relative = str_replace('App\\', '', $class);
        $path = str_replace('\\', '/', $relative);
        $file = __DIR__ . '/../' . $path . ".php";
        if(file_exists($file)){
            require_once $file;
        }
    });
?>