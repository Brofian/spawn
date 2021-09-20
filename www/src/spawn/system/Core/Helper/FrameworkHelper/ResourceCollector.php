<?php

namespace spawn\system\Core\Helper\FrameworkHelper;

use RecursiveIteratorIterator;
use spawn\system\Core\Base\Custom\FileEditor;
use spawn\system\Core\Contents\Modules\Module;
use spawn\system\Core\Contents\Modules\ModuleCollection;
use spawn\system\Core\Helper\URIHelper;


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

        $scssIndexFile = "";
        $jsIndexFile = "";

        $entryPointCss = URIHelper::joinMultiplePaths(   self::RESOURCE_CACHE_FOLDER, 'modules', 'scss', 'index.scss');
        $entryPointJs = URIHelper::joinMultiplePaths(    self::RESOURCE_CACHE_FOLDER, 'modules', 'js',   'index.js');
        $entryPointAssets = URIHelper::joinMultiplePaths(self::RESOURCE_CACHE_FOLDER_PUBLIC, "assets");

        foreach($moduleCollection->getModuleList() as $module) {

            //move the modules from this namespace
            $this->moveModuleData($module, $scssIndexFile, $jsIndexFile, $entryPointCss, $entryPointJs, $entryPointAssets);
        }

        FileEditor::createFile($entryPointCss, "/* Index File - generated automatically*/" . PHP_EOL . PHP_EOL . $scssIndexFile);
        FileEditor::createFile($entryPointJs,  "/* Index File - generated automatically*/" . PHP_EOL . PHP_EOL . $jsIndexFile);


    }


    private function moveModuleData(Module $module, &$scssIndexFile, &$jsIndexFile, $entryPointCss, $entryPointJs, $entryPointAssets) {

        /*
         * SCSS
         */
        $scssFolder = URIHelper::joinMultiplePaths(ROOT, $module->getBasePath(), $module->getResourcePath(), "public", "scss");

        if(file_exists($scssFolder . "/base.scss")) {
            $scssIndexFile .= "@import \"{$module->getName()}/base\";" . PHP_EOL;
        }
        if(file_exists($scssFolder . "/_global/base.scss")) {
            $scssIndexFile = "@import \"{$module->getName()}/_global/base\";" . PHP_EOL . $scssIndexFile;
        }
        self::copyFolderRecursive($scssFolder, dirname($entryPointCss) . DIRECTORY_SEPARATOR . $module->getName());


        /*
         * Javascript
         */
        $jsFolder = URIHelper::joinMultiplePaths(ROOT, $module->getBasePath(), $module->getResourcePath(), "public", "js");

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
        $assetsFolder = URIHelper::joinMultiplePaths(ROOT, $module->getBasePath(), $module->getResourcePath(), "public", "assets");
        self::copyFolderRecursive($assetsFolder, $entryPointAssets);

    }




    public static function copyFolderRecursive(string $source, string $dest) {

        URIHelper::pathifie($source, DIRECTORY_SEPARATOR, false);
        URIHelper::pathifie($dest, DIRECTORY_SEPARATOR, false);

        if(!file_exists($source)) {
            return;
        }


        FileEditor::createFolder($dest);

        /** @var RecursiveIteratorIterator $iterator */
        foreach (
            $iterator = new RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST)
            as $item
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