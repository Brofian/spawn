<?php

    require_once("../src/webu/init.php");

    //load environment
    $environment = new \webu\system\Environment();

    echo $environment->finish();


?>

