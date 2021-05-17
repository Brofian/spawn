<?php

use webu\system\Core\Contents\Modules\ModuleCollection;
use webu\system\Core\Base\Helper\DatabaseHelper;
use webuApp\Models\ModuleStorage;
use webu\system\Core\Contents\Modules\Module;
use bin\webu\IO;
use webu\system\Core\Contents\Modules\ModuleController;
use webu\system\Core\Contents\Modules\ModuleAction;
use webuApp\Models\ModuleActionStorage;


$dbHelper = new DatabaseHelper();
$migrationTableExists = $dbHelper->doesTableExist('webu_migrations');



/** @var ModuleCollection $moduleCollection */
$moduleCollection = include(__DIR__ . "/callable/list-modules.php");
$existingModules = ModuleStorage::findAll($dbHelper->getConnection());


//check for new modules

$newModules = [];
/** @var Module $module */
foreach($moduleCollection->getModuleList() as $module) {

    $isAlreadyRegistered = false;
    /** @var ModuleStorage $existingModule */
    foreach($existingModules as $existingModule) {
        if($existingModule->getPath() == $module->getBasePath()) {
            $isAlreadyRegistered = true; break;
        }
    }

    if($isAlreadyRegistered) {
        continue;
    }
    else {
        $newModules[] = $module;
    }
}




//save new modules
$count = 0;
foreach($newModules as $module) {
    $resourceConfig = json_encode([
        "namespace" => $module->getResourceNamespace(),
        "using" => $module->getUsingNamespaces(),
        "path" => $module->getResourcePath(),
        "weight" => $module->getResourceWeight()
    ]);
    $informations = json_encode($module->getInformation());

    $moduleStorage = new ModuleStorage(
        $module->getSlug(),
        $module->getBasePath(),
        false,
        $informations,
        $resourceConfig
    );
    $moduleStorage->save($dbHelper->getConnection());
    $moduleId = $moduleStorage->getId();
    $count++;

    //save actions
    /** @var ModuleController $controller */
    foreach($module->getModuleControllers() as $controller) {
        /** @var ModuleAction $action */
        foreach($controller->getActions() as $action) {
            $moduleAction = new ModuleActionStorage(
                $controller->getClass(),
                $action->getAction(),
                $action->getCustomUrl(),
                $moduleId
            );
            $moduleAction->save($dbHelper->getConnection());
        }
    }
}


//delete missing modules
/** @var ModuleStorage $module */
foreach($existingModules as $module) {
    if(!$module->isActive() && !file_exists($module->getPath())) {
        $module->delete($dbHelper->getConnection());
    }
}



IO::printLine("> Successfully refreshed Modules", IO::GREEN_TEXT);