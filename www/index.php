<?php declare(strict_types=1);


    require_once("../src/spawn/init.php");

    //load environment
    $environment = new \spawn\system\Environment();

    echo $environment->handle();



