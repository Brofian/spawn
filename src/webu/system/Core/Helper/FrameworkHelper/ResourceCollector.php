<?php

namespace webu\system\Core\Helper\FrameworkHelper;

use RecursiveIteratorIterator;
use webu\system\Core\Base\Custom\FileCrawler;
use webu\system\Core\Base\Custom\FileEditor;
use webu\system\Core\Contents\Modules\Module;
use webu\system\Core\Contents\Modules\ModuleCollection;
use webu\system\Core\Helper\URIHelper;


class ResourceCollector {

    const RESOURCE_CACHE_FOLDER = ROOT . CACHE_DIR . "/private/resources";



    public static function isGatheringNeeded() : bool {
        return file_exists(self::RESOURCE_CACHE_FOLDER);
    }

    public function gatherModuleData(ModuleCollection $moduleCollection) {

        $sortedModules = $this->sortModuleCollectionByNamespace($moduleCollection);

        foreach($sortedModules as $namespace => $modules) {
            $namespace = ($namespace=="") ? "default" : $namespace;

            $entryPointCss = self::RESOURCE_CACHE_FOLDER . DIRECTORY_SEPARATOR . ($namespace=="" ? "" : $namespace . DIRECTORY_SEPARATOR) . "scss" . DIRECTORY_SEPARATOR . "index.scss";
            $entryPointJs = self::RESOURCE_CACHE_FOLDER . DIRECTORY_SEPARATOR . ($namespace=="" ? "" : $namespace . DIRECTORY_SEPARATOR) . "js" . DIRECTORY_SEPARATOR . "index.js";



            //scss
            $scssIndexFile = "/* Index File - generated automatically*/" . PHP_EOL . PHP_EOL;
            $jsIndexFile = "/* Index File - generated automatically*/" . PHP_EOL . PHP_EOL;

            /** @var Module $module */
            foreach($modules as $module) {

                /*
                 * SCSS
                 */
                $scssFolder = $module->getResourcePath() . "/public/scss";
                if(file_exists($scssFolder . "/base.scss")) {
                    $scssIndexFile .= "@import \"{$module->getName()}/base\";\n";
                }
                self::copyFolderRecursive($scssFolder, dirname($entryPointCss) . DIRECTORY_SEPARATOR . $module->getName());


                /*
                 * Javascript
                 */
                $jsFolder = $module->getResourcePath() . "/public/js";
                if(file_exists($jsFolder . "/main.js")) {
                    $jsIndexFile .= "import \"./{$module->getName()}/main.js\";\n";
                }
                self::copyFolderRecursive($jsFolder, dirname($entryPointJs) . DIRECTORY_SEPARATOR . $module->getName());
            }

            FileEditor::createFile($entryPointCss, $scssIndexFile);
            FileEditor::createFile($entryPointJs, $jsIndexFile);

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
                    mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
                }
            } else {
                copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            }
        }

    }







}