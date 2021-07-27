<?php declare(strict_types=1);

if (PHP_VERSION_ID < 70400) {
    echo 'You´re currently running ' . PHP_VERSION . '. Please Upgrade to PHP 7.4.0 or newer' . PHP_EOL;
    exit();
}

\bin\spawn\IO::$onCommandLine = true;

include __DIR__ . "/../src/spawn/init.php";

$console = new spawn\bin\Console($argv);



