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

    /** @var string  */
    const RESOURCE_CACHE_FOLDER = ROOT . CACHE_DIR . "/private/resources";
    /** @var string  */
    const RESOURCE_CACHE_FOLDER_PUBLIC = ROOT . CACHE_DIR . "/public";


    /**
     * @return bool
     */
    public static function isGatheringNeeded() : bool {
        return !file_exists(self::RESOURCE_CACHE_FOLDER);
    }

    /**
     * @param ModuleCollection $moduleCollection
     */
    public function gatherModuleData(ModuleCollection $moduleCollection) {

        $sortedModules = $this->sortModuleCollectionByNamespace($moduleCollection);

        $sortedModules = $this->enrichNamespacesByUsedNamespaces($sortedModules);

        $sortedModules = $this->removeUnusedNamespaces($sortedModules);

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

    /**
     * @param $namespace
     * @param $modules
     * @param $scssIndexFile
     * @param $jsIndexFile
     * @param $entryPointCss
     * @param $entryPointJs
     * @param $entryPointAssets
     */
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


    /**
     * @param ModuleCollection $moduleCollection
     * @return array
     */
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


    /**
     * @param string $source
     * @param string $dest
     */
    public static function copyFolderRecursive(string $source, string $dest) {
        URIHelper::pathifie($source, DIRECTORY_SEPARATOR, false);
        URIHelper::pathifie($dest, DIRECTORY_SEPARATOR, false);

        FileEditor::createFolder($dest);

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


    /**
     * @param array $namespaces
     * @return array
     */
    private function enrichNamespacesByUsedNamespaces(array $namespaces) {

        $newNamespaces = array();

        foreach($namespaces as $namespace => $modulesArray) {
            $includedNamespaces = [$namespace];

            $madeChanges = false;
            $recursiveProtection = 0;
            //info: Iterate over all modules, that are included in this namespace and include the modules from the referenced ones
            //info: Then check if they themself use another namespace
            do {
                $madeChanges = false;

                /** @var Module $module */
                foreach($modulesArray as $module) {

                    foreach($module->getUsingNamespaces() as $usingNamespace) {
                        //info: usingNamespaces are the raw namespace, so hash them
                        $hashedNamespace = ModuleNamespacer::hashRawNamespace($usingNamespace);

                        //info: only include the namespace, if it isnt included by now
                        if(!in_array($hashedNamespace, $includedNamespaces) && isset($namespaces[$hashedNamespace])) {
                            $modulesArray = array_merge($modulesArray, $namespaces[$hashedNamespace]);

                            $madeChanges = true;
                        }

                        $includedNamespaces[] = $hashedNamespace;
                    }
                }
            }
            while($madeChanges && $recursiveProtection < 50);

            $newNamespaces[$namespace] = $modulesArray;

            unset($includedNamespaces);
        }


        return $newNamespaces;
    }


    /**
     * @param array $namespaces
     * @return array
     */
    public function removeUnusedNamespaces(array $namespaces) {
        $newNamespaces = array();

        /**
         * @var string $namespace
         * @var array $modulesArray
         */
        foreach($namespaces as $namespace => $modulesArray) {
            $isUsed = false;



            /** @var Module $module */
            foreach($modulesArray as $module) {
                if($module->isActive() && $module->getResourceNamespace() == $namespace) {
                    //info: this module is an original and active module from the namespace!
                    //info: this means, the namespace is used!
                    $isUsed = true;
                    break;
                }
            }

            if($isUsed) {
                $newNamespaces[$namespace] = $modulesArray;
            }
        }

        return $newNamespaces;
    }




}