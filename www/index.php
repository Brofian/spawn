<?php

    require("../src/vendor/webu/.autoloader/Autoloader.php");
    require("../config.php");

    //set some default values
    define('ROOT', dirname(__DIR__) );
    define('RELROOT', ".." );


    //prepare global autoload class
    global $autoloadClass;
    $autoloadClass = new \webu\autoloader\Autoloader();
    $autoloadClass->alwaysReload = ($config["mode"] == "dev");

    //set autloader
    spl_autoload_register(
        function($className) use ($autoloadClass) {
            $autoloadClass->autoload($className);
        },
        ($config["mode"] == "dev")
    );



    $environment = new \webu\system\Environment($config);


?>

