<?php

namespace webu\system\Core\Contents\Modules;


use webu\system\Core\Base\Custom\FileEditor;

class ModuleCacher {

    const MODULE_CACHE_FILE = ROOT . CACHE_DIR . "\\private\\generated\\modules\\module_cache.json";


    public static function createModuleCache(ModuleCollection $moduleCollection) {
        $collectionArray = array();

        /** @var Module $module */
        foreach($moduleCollection->getModuleList() as $module) {

            $moduleControllerArray = array();
            /** @var ModuleController $moduleController */
            foreach($module->getModuleControllers() as $id => $moduleController) {
                $moduleControllerArray[$id] = [
                    "class" => $moduleController->getClass(),
                    "actions" => $moduleController->getActions(),
                    "id" => $moduleController->getId()
                ];
            }

            $moduleArray = [
                "informations" => $module->getInformation(),
                "moduleName" => $module->getName(),
                "moduleControllers" => $moduleControllerArray,
                "basePath" => $module->getBasePath(),
                "resourcePath" => $module->getRelativeResourcePath(),
                "resourceWeight" => $module->getResourceWeight()
            ];


            $collectionArray[] = $moduleArray;

            FileEditor::createFile(self::MODULE_CACHE_FILE, json_encode($collectionArray));
        }



    }


    public static function readModuleCache() {

        if(!file_exists(self::MODULE_CACHE_FILE)) return false;

        $moduleCollectionArray = json_decode(FileEditor::getFileContent(self::MODULE_CACHE_FILE));

        //Convert Array to Object
        $moduleCollection = new ModuleCollection();


        foreach($moduleCollectionArray as $moduleArray) {
            $module = new Module(
                $moduleArray->moduleName
            );
            $module->setBasePath($moduleArray->basePath);
            $module->setResourcePath($moduleArray->resourcePath);
            $module->setResourceWeight($moduleArray->resourceWeight);

            foreach($moduleArray->moduleControllers as $id => $moduleControllerArray) {
                $moduleController = new ModuleController($id, $moduleControllerArray->class, (array)$moduleControllerArray->actions);
                $module->addModuleController($moduleController);
            }


            foreach($moduleArray->informations as $key => $information) {
                $module->setInformation($key, $information);
            }

            $moduleCollection->addModule($module);
        }


        return $moduleCollection;
    }

}