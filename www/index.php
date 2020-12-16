<?php

    require_once("../src/webu/init.php");


    //load _system environment
    $environment = new \webu\system\Environment();

    echo $environment->finish();


?>

