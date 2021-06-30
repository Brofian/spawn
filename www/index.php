<?php declare(strict_types=1);


    require_once("../src/webu/init.php");

    //load environment
    $environment = new \webu\system\Environment();

    echo $environment->handle();



