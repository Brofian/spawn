<?php declare(strict_types=1);

    require_once(__DIR__."/../src/spawn/init.php");

    //load environment
    $environment = new \spawnCore\CardinalSystem\Environment();

    echo $environment->handle();



