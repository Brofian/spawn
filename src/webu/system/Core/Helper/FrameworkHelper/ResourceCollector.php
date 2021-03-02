<?php

namespace webu\system\Core\Helper\FrameworkHelper;

use RecursiveIteratorIterator;
use webu\system\Core\Base\Custom\FileCrawler;
use webu\system\Core\Base\Custom\FileEditor;
use webu\system\Core\Contents\Modules\Module;
use webu\system\Core\Contents\Modules\ModuleCollection;
use webu\system\Core\Contents\Modules\ModuleNamespacer;
use webu\system\Core\Helper\URIHelper;


class ResourceCollector {

    const RESOURCE_CACHE_FOLDER = ROOT . CACHE_DIR . "/private/resources";
    const RESOURCE_CACHE_FOLDER_PUBLIC = ROOT . CACHE_DIR . "/public";



    public static function isGatheringNeeded() : bool {
        return file_exists(self::RESOURCE_CACHE_FOLDER);
    }

    public function gatherModuleData(ModuleCollection $moduleCollection) {

        $sortedModules = $this->sortModuleCollectionByNamespace($moduleCollection);

        $globalNamespace = ModuleNamespacer::getGlobalNamespace();

        foreach($sortedModules as $namespace => $modules) {
            $entryPointCss = self::RESOURCE_CACHE_FOLDER . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR . "scss" . DIRECTORY_SEPARATOR . "index.scss";
            $entryPointJs = self::RESOURCE_CACHE_FOLDER . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR . "js" . DIRECTORY_SEPARATOR . "index.js";
            $entryPointAssets = self::RESOURCE_CACHE_FOLDER_PUBLIC . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR . "assets";

            $scssIndexFile = "";
            $jsIndexFile = "";

            //move the modules from this namespace
            ModuleCollection::sortModulesByWeight($modules);
            $this->moveModuleData($namespace, $modules, $scssIndexFile, $jsIndexFile, $entryPointCss, $entryPointJs, $entryPointAssets);

            FileEditor::createFile($entryPointCss, "/* Index File - generated automatically*/" . PHP_EOL . PHP_EOL . $scssIndexFile);
            FileEditor::createFile($entryPointJs,  "/* Index File - generated automatically*/" . PHP_EOL . PHP_EOL . $jsIndexFile);
        }

    }


    private function moveModuleData($namespace, $modules, &$scssIndexFile, &$jsIndexFile, $entryPointCss, $entryPointJs, $entryPointAssets) {
        /** @var Module $module */
        foreach($modules as $module) {

            /*
             * SCSS
             */
            $scssFolder = $module->getResourcePath() . "/public/scss";
            if(file_exists($scssFolder . "/base.scss")) {
                $scssIndexFile .= "@import \"{$module->getName()}/base\";\n";
            }
            if(file_exists($scssFolder . "/_global/base.scss")) {
                $scssIndexFile = "@import \"{$module->getName()}/_global/base\";\n" . $scssIndexFile;
            }
            self::copyFolderRecursive($scssFolder, dirname($entryPointCss) . DIRECTORY_SEPARATOR . $module->getName());


            /*
             * Javascript
             */
            $jsFolder = $module->getResourcePath() . "/public/js";
            if(file_exists($jsFolder . "/main.js")) {
                $jsIndexFile .= "import \"./{$module->getName()}/main.js\";\n";
            }
            if(file_exists($jsFolder . "/_global/main.js")) {
                $jsIndexFile = "import \"./{$module->getName()}/_global/main.js\";\n" . $jsIndexFile;
            }
            self::copyFolderRecursive($jsFolder, dirname($entryPointJs) . DIRECTORY_SEPARATOR . $module->getName());

            /*
             * Assets
             */
            $assetsFolder = $module->getResourcePath() . "/public/assets";
            self::copyFolderRecursive($assetsFolder, $entryPointAssets);

        }

    }





    public function sortModuleCollectionByNamespace(ModuleCollection $moduleCollection) {
        $sortedModuleCollection = array();

        /** @var Module $module */
        foreach($moduleCollection->getModuleList() as $module) {

            if(!isset($sortedModuleCollection[$module->getResourceNamespace()])) {
                $sortedModuleCollection[$module->getResourceNamespace()] = array();
            }

            $sortedModuleCollection[$module->getResourceNamespace()][] = $module;
        }


        return $sortedModuleCollection;
    }





    public static function copyFolderRecursive(string $source, string $dest) {
        URIHelper::pathifie($source, DIRECTORY_SEPARATOR, false);
        URIHelper::pathifie($dest, DIRECTORY_SEPARATOR, false);


        if(!file_exists($dest)) {
            FileEditor::createFolder($dest);
        }




        foreach (
            /** @var RecursiveIteratorIterator $iterator */
            $iterator = new RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if ($item->isDir()) {
                if(!file_exists($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName())) {
                    FileEditor::createFolder($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
                }
            } else {
                copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            }
        }

    }







}