<?php

    //set some default values
    define('ROOT', dirname(__DIR__) );
    define('RELROOT', ".." );

    //load config
    include("../config.php");
    if(!defined('MODE')) {
        echo "Please create the config.php file with the sample pattern";
        die();
    }


    //load autoloader
    require("../src/webu/.autoloader/AutoloadInit.php");
    //load twig autoloader
    require_once("../vendor/autoload.php");

    //load system environment
    $environment = new \webu\system\Environment();


?>

