<?php declare(strict_types=1);

require(ROOT . "/vendor/autoload.php");

require("Autoloader.php");


//prepare global autoload class
global $autoloadClass;
$autoloadClass = new \webu\autoloader\Autoloader();
$autoloadClass->alwaysReload = (MODE == "dev");

//set autloader
spl_autoload_register(
    function ($className) use ($autoloadClass) {
        $autoloadClass->autoload($className);
    },
    (MODE == "dev")
);
