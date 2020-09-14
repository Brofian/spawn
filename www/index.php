<?php

    //set some default values
    define('ROOT', dirname(__DIR__) );
    define('RELROOT', ".." );

    //load config
    require("../config.php");

    //load autoloader
    require("../src/vendor/webu/.autoloader/AutoloadInit.php");

    //load system environment
    $environment = new \webu\system\Environment();


?>

