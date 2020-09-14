<?php

    require("../config.php");
    require("../src/vendor/webu/.autoloader/AutoloadInit.php");

    //set some default values
    define('ROOT', dirname(__DIR__) );
    define('RELROOT', ".." );




    $environment = new \webu\system\Environment();


?>

