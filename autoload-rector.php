<?php

spl_autoload_register(function ($name) {
    //echo "Möchte $name laden.\n";

    $file = "";

    if (strpos($name, "Controller")) {
        $file = __DIR__ . "/application/B2B/controllers/" . $name . '.php';
    } else if (strpos($name, "Trait")) {
        $className = explode("_", $name);
        $file = __DIR__ . "/library/Daiber/Application/Trait/" . end($className) . '.php';
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
