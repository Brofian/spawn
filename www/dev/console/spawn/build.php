<?php declare(strict_types=1);


include(__DIR__ . "/callable/print-spawn.php");

//clear all caches
include(__DIR__ . "/../cache/clear.php");

//upsert database
include(__DIR__ . "/../database/update.php");

//update module list
include(__DIR__ . "/../modules/refresh.php");

//compile modules
include(__DIR__ . "/../modules/compile-js.php");
include(__DIR__ . "/../modules/compile-scss.php");