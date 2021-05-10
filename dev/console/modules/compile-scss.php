<?php


use bin\webu\IO;
use webu\system\Core\Helper\ScssHelper;
use webu\system\Core\Helper\URIHelper;


//load resources from modules
include(__DIR__ . "/gather-files.php");

$moduleCollection = include(__DIR__ . "/callable/list-modules.php");

$scssHelper = new ScssHelper();

$errorCodeSum = 0;

IO::printLine("> compiling SCSS", IO::YELLOW_TEXT);

foreach($moduleCollection->getNamespaceList() as $raw_namespace =>$namespace) {

    $scssHelper->setBaseVariable("assetsPath", URIHelper::createPath([MAIN_ADDRESS_FULL,CACHE_DIR,"public",$namespace,"assets"], "/"));
    $scssHelper->setBaseVariable("defaultAssetsPath", URIHelper::createPath([MAIN_ADDRESS_FULL,CACHE_DIR,"public",$namespace,"assets"], "/"));

    try {
        $scssHelper->createCss($moduleCollection);
    }
    catch(Exception $e) {
        IO::printLine("An Error occurred! There is probably more output above", IO::RED_TEXT);
        die();
    }


    IO::printLine(IO::TAB . "Compiled " . $namespace);
}


IO::printLine("> - successfully compiled SCSS", IO::GREEN_TEXT);