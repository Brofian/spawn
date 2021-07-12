<?php

use spawn\system\Core\Contents\Modules\ModuleCollection;
use spawn\system\Core\Base\Helper\DatabaseHelper;
use spawnApp\Models\ModuleStorage;
use spawn\system\Core\Contents\Modules\Module;
use bin\spawn\IO;
use spawn\system\Core\Contents\Modules\ModuleLoader;

$dbHelper = new DatabaseHelper();
$migrationTableExists = $dbHelper->doesTableExist('spawn_migrations');



/** @var ModuleLoader $moduleLoader */
$moduleLoader = new ModuleLoader();
/** @var ModuleCollection $moduleCollection */
$moduleCollection = $moduleLoader->readModules($dbHelper->getConnection());
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



IO::printLine("> Save new Modules and actions", IO::YELLOW_TEXT);

//save new modules
$count = 0;
foreach($newModules as $module) {
    $resourceConfig = json_encode([
        "namespace" => $module->getResourceNamespace(),
        "namespace_raw" => $module->getResourceNamespaceRaw(),
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
}

IO::printLine("> Deleting old modules", IO::YELLOW_TEXT);

//delete missing modules
/** @var ModuleStorage $module */
foreach($existingModules as $module) {
    if(!$module->isActive() && !file_exists($module->getPath())) {
        $module->delete($dbHelper->getConnection());
        continue;
    }
}


IO::printLine("> - Successfully refreshed Modules", IO::GREEN_TEXT);