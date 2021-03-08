<?php

use \webu\system\Core\Contents\Modules\ModuleLoader;
use \webu\system\Core\Helper\FrameworkHelper\ResourceCollector;
use \webu\system\Core\Contents\Modules\Module;
use \bin\webu\IO;
use \src\npm\WebpackConfigGenerator;



$errorCodeSum = 0;


$moduleLoader = new ModuleLoader();
$moduleCollection = $moduleLoader->loadModules(ROOT . "/modules");

IO::printLine("> gathering files from modules...");


/** @var Module $module */
foreach($moduleCollection->getModuleList() as $module) {
    IO::printLine(IO::TAB . "- " . $module->getName());
}

$resourceCollector = new ResourceCollector();
$resourceCollector->gatherModuleData($moduleCollection);

IO::printLine("> compiling javascript...");



//javascript kompilieren
$webpackDir = ROOT . "/src/npm";


foreach($moduleCollection->getNamespaceList() as $namespace) {

    if(!file_exists(ResourceCollector::RESOURCE_CACHE_FOLDER . "/" . $namespace . "/js/index.js")) {
        //info: it is possible for an inactive module to appear in the namespace list, but have no files collected
        //info: this is totally fine, just skip the namespace
        continue;
    }

    WebpackConfigGenerator::rewriteConf($namespace, $moduleCollection->getNamespaceList());

    $result = IO::execInDir("npx webpack --config webpack.config.js --progress", $webpackDir, false, $code);
    $errorCodeSum += $code;
    IO::printLine(IO::TAB . "Compiled " . $namespace);

}






if($errorCodeSum == 0) {
    IO::printLine("All commands executed successfully", IO::GREEN_TEXT);
}
else {
    IO::printLine("An Error occurred! There is probably more output above", IO::RED_TEXT);
}
