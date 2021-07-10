<?php declare(strict_types=1);


use bin\spawn\IO;
use spawn\system\Core\Helper\ScssHelper;
use spawn\system\Core\Helper\URIHelper;


//load resources from modules
include(__DIR__ . "/gather-files.php");

$moduleCollection = include(__DIR__ . "/callable/list-modules.php");

$scssHelper = new ScssHelper();

$errorCodeSum = 0;

IO::printLine("> compiling SCSS", IO::YELLOW_TEXT);



$scssHelper->setBaseVariable("assetsPath", URIHelper::createPath([MAIN_ADDRESS_FULL,CACHE_DIR,"public","assets"], "/"));
$scssHelper->setBaseVariable("defaultAssetsPath", URIHelper::createPath([MAIN_ADDRESS_FULL,CACHE_DIR,"public","assets"], "/"));

try {
    $scssHelper->createCss($moduleCollection);
}
catch(Exception $e) {
    IO::printLine("An Error occurred! There is probably more output above", IO::RED_TEXT);
    die();
}



IO::printLine("> - successfully compiled SCSS", IO::GREEN_TEXT);
