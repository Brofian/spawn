<?php


//set some default values
define('ROOT', dirname(__DIR__, 2) );
define('CACHE_DIR', '/var/cache');
define('IS_TERMINAL', defined('STDIN'));


//load config
require(ROOT ."/config.php");
if(!defined('MODE')) {
    echo "Please create the config.php file with the sample pattern";
    die();
}

//load custom elements
require(ROOT . "/dev/main.php");


//load autoloader
require(ROOT . "/src/spawn/.autoloader/AutoloadInit.php");
//load twig autoloader
require_once(ROOT . "/vendor/autoload.php");