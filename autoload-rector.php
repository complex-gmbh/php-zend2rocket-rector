<?php

spl_autoload_register(function ($name) {
    $controllerDir = '/application/B2B/controllers/';
    $traitDir = '/library/TENANT/Application/Trait/';

    $file = "";
    if (strpos($name, "Controller")) {
        $file = __DIR__ . $controllerDir . $name . '.php';
    } else if (strpos($name, "Trait")) {
        $className = explode("_", $name);
        $file = __DIR__ . $traitDir . end($className) . '.php';
    }

    if (file_exists($file))
    {
        require_once $file;
    }
    else
    {
        return false;
    }
});

// composer autoloader
require_once "vendor/autoload.php";
