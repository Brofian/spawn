<?php


//set some default values
define('ROOT', dirname(dirname(__DIR__)) );

//load config
require(ROOT . "/config.php");
if(!defined('MODE')) {
    echo "Please create the config.php file with the sample pattern";
    die();
}

//load custom elements
require(ROOT . "/dev/main.php");


//load autoloader
require(ROOT . "/src/webu/.autoloader/AutoloadInit.php");
//load twig autoloader
require_once(ROOT . "/vendor/autoload.php");