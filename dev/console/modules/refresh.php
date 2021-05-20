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



IO::printLine("> Save new Modules and actions", IO::YELLOW_TEXT);

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
                $action->getId(),
                $moduleId
            );
            $moduleAction->save($dbHelper->getConnection());
        }
    }
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


//update actions
/** @var ModuleStorage $module */
foreach($existingModules as $module) {
    if($module->getId() === null) continue;

    IO::printLine("> Checking Module \"".$module->getSlug()."\"", IO::YELLOW_TEXT);


    $existingActions = ModuleActionStorage::findAll($dbHelper->getConnection(),$module->getId());
    $checkedActions = [];
    $newActions = 0;
    $updatedActions = 0;

    /** @var Module $moduleObj */
    foreach($moduleCollection->getModuleList() as $moduleObj) {
        if($moduleObj->getSlug() != $module->getSlug() ) {
            continue;
        }

        /** @var ModuleController $moduleObjController */
        foreach($moduleObj->getModuleControllers() as $moduleObjController) {

            /** @var ModuleAction $moduleObjAction */
            foreach($moduleObjController->getActions() as $moduleObjAction) {

                //search for this class-action combination in the existing
                $foundActionInDatabase = false;
                /** @var ModuleActionStorage $existingAction */
                foreach($existingActions as $existingAction) {
                    if(
                        $existingAction->getClass()     == $moduleObjController->getClass() &&
                        $existingAction->getAction()    == $moduleObjAction->getAction() &&
                        $existingAction->getIdentifier() == $moduleObjAction->getId()
                    ) {
                        //this action is saved in the database
                        $foundActionInDatabase = true;

                        if(!isset($checkedActions[$existingAction->getIdentifier()])) {
                            $checkedActions[$existingAction->getIdentifier()] = $existingAction;
                        }

                        if($existingAction->getCustomUrl() != $moduleObjAction->getCustomUrl()) {
                            $existingAction->setCustomUrl($moduleObjAction->getCustomUrl());
                            $existingAction->save($dbHelper->getConnection());
                            $updatedActions++;
                        }
                    }
                }

                if(!$foundActionInDatabase) {
                    //save action to database
                    $actionStrg = new ModuleActionStorage(
                        $moduleObjController->getClass(),
                        $moduleObjAction->getAction(),
                        $moduleObjAction->getCustomUrl(),
                        $moduleObjAction->getId(),
                        $module->getId()
                    );
                    $actionStrg->save($dbHelper->getConnection());
                    $newActions++;
                }
            }
        }
    }

    if($updatedActions > 0) {
        IO::printLine(IO::TAB . "> Updated $updatedActions existing actions");
    }
    if($newActions > 0) {
        IO::printLine(IO::TAB . "> Added $newActions new Actions");
    }



    $numExisting = count($existingActions); // Datenbank Einträge
    $numTotal = count($checkedActions); // Datenbank Einträge, die auch existieren

    if($numExisting <= $numTotal) {
        continue;
    }


    IO::printLine(IO::TAB . "> Deleting ".($numExisting-$numTotal)." old actions");

    //delete not found actions
    /** @var ModuleActionStorage $existingAction */
    foreach($existingActions as $existingAction) {
        $keepAction = false;
        /** @var ModuleActionStorage $checkedAction */
        foreach($checkedActions as $checkedAction) {
            if($checkedAction->equals($existingAction)) {
                $keepAction = true;
                break;
            }
        }

        if(!$keepAction) {
            //delete action
            $existingAction->delete($dbHelper->getConnection());
        }
    }

}

IO::printLine("> - Successfully refreshed Modules", IO::GREEN_TEXT);