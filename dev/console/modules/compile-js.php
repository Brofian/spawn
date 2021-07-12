<?php


use bin\webu\IO;
use src\npm\WebpackConfigGenerator;
use webu\system\Core\Contents\Modules\ModuleLoader;

//load resources from modules
include(__DIR__ . "/gather-files.php");

include_once(__DIR__ . "/../npm/addNodeJsToPath.php");

/** @var \webu\system\Core\Contents\Modules\ModuleCollection $moduleCollection */
$moduleCollection = include(__DIR__ . "/callable/list-modules.php");
$errorCodeSum = 0;
$webpackDir = ROOT . "/src/npm";


//javascript kompilieren
IO::printLine("> compiling JavaScript", IO::YELLOW_TEXT);

foreach($moduleCollection->getNamespaceList() as $namespace) {

    WebpackConfigGenerator::rewriteConf($namespace, $moduleCollection->getNamespaceList());

    $result = IO::execInDir("npx webpack --config webpack.config.js --progress", $webpackDir, false, $code);
    $errorCodeSum += $code;
    IO::printLine(IO::TAB . "Compiled " . $namespace);
}




if($errorCodeSum > 0) {
    IO::printLine("An Error occurred! There is probably more output above", IO::RED_TEXT);
    die();
}
else {
    IO::printLine("> - successfully compiled JavaScript", IO::GREEN_TEXT);
}
