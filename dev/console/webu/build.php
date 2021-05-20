<?php


include(__DIR__ . "/callable/print-webu.php");

//clear all caches
include(__DIR__ . "/../cache/clear.php");

//update module list
include(__DIR__ . "/../modules/refresh.php");

//compile modules
include(__DIR__ . "/../modules/compile-js.php");
include(__DIR__ . "/../modules/compile-scss.php");