<?php

if (PHP_VERSION_ID < 70400) {
    echo 'You´re currently running ' . PHP_VERSION . '. Please Upgrade to PHP 7.4.0 or newer' . PHP_EOL;
    exit();
}

include __DIR__ . "/../src/webu/init.php";

$console = new webu\bin\Console($argv);



