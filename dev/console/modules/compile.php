<?php

use \webu\system\Core\Contents\Modules\ModuleLoader;
use \webu\system\Core\Helper\FrameworkHelper\ResourceCollector;
use \webu\system\Core\Contents\Modules\Module;
use \bin\webu\IO;
use \webu\system\Core\Base\Custom\FileEditor;



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


$layout = FileEditor::getFileContent($webpackDir . "/webpack.config.js.layout");


foreach($moduleCollection->getNamespaceList() as $namespace) {

    //create config file
    $configFileContent = $layout;
    $configFileContent = str_replace("{{namespace}}", $namespace, $configFileContent);
    $configCreated = FileEditor::createFile($webpackDir . "/webpack.config.js", $configFileContent);


    if($configCreated) {
        $result = IO::execInDir("npx webpack --config webpack.config.js", $webpackDir, false, $code);
        $errorCodeSum += $code;
        IO::printLine(IO::TAB . "Compiled " . $namespace);
    }
    else {
        IO::printLine(IO::TAB . "Could not create config for " . $namespace . "! Skipping...", IO::YELLOW_TEXT);
    }

}






if($errorCodeSum == 0) {
    IO::printLine("All commands executed successfully", IO::GREEN_TEXT);
}
else {
    IO::printLine("An Error occurred! There is probably more output above", IO::RED_TEXT);
}
