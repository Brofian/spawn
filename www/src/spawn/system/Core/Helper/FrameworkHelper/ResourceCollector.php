<?php

namespace spawn\system\Core\Helper\FrameworkHelper;

use RecursiveIteratorIterator;
use spawn\system\Core\Base\Custom\FileEditor;
use spawn\system\Core\Contents\Modules\Module;
use spawn\system\Core\Contents\Modules\ModuleCollection;


class ResourceCollector {

    const PUBLIC_ASSET_PATH = ROOT . '/public/pack/';
    const RESOURCE_CACHE_PATH = ROOT . '/var/cache/resources/modules';


    /**
     * @return bool
     */
    public static function isGatheringNeeded() : bool {
        return true;
    }

    /**
     * @param ModuleCollection $moduleCollection
     */
    public function gatherModuleData(ModuleCollection $moduleCollection) {

        $scssIndexFile = "";
        $jsIndexFile = "";

        foreach($moduleCollection->getModuleList() as $module) {
            //move the modules from this namespace
            $this->moveModuleData($module, $scssIndexFile, $jsIndexFile);
        }

        //create entry file for css and js compilation
        FileEditor::createFile(
            self::RESOURCE_CACHE_PATH.'/scss/index.scss',
            "/* Index File - generated automatically*/" . PHP_EOL . PHP_EOL . $scssIndexFile
        );
        FileEditor::createFile(
            self::RESOURCE_CACHE_PATH.'/js/index.js',
            "/* Index File - generated automatically*/" . PHP_EOL . PHP_EOL . $jsIndexFile
        );
    }


    private function moveModuleData(Module $module, &$scssIndexFile, &$jsIndexFile) {

        if(!$module->getResourcePath()) {
            return;
        }

        $absoluteModuleResourcePath = $module->getAbsoluteBasePath() . $module->getResourcePath();

        /*
         * SCSS
         */
        $scssFolder = $absoluteModuleResourcePath . '/public/scss';
        if(file_exists($scssFolder . "/base.scss")) {
            $scssIndexFile .= "@import \"{$module->getSlug()}/base\";" . PHP_EOL;
        }
        if(file_exists($scssFolder . "/_global/base.scss")) {
            $scssIndexFile = "@import \"{$module->getSlug()}/_global/base\";" . PHP_EOL . $scssIndexFile;
        }
        self::copyFolderRecursive($scssFolder, self::RESOURCE_CACHE_PATH .'/scss/'. $module->getSlug());


        /*
         * Javascript
         */
        $jsFolder = $absoluteModuleResourcePath . '/public/js';
        if(file_exists($jsFolder . "/main.js")) {
            $jsIndexFile .= "import \"./{$module->getSlug()}/main.js\";\n";
        }
        if(file_exists($jsFolder . "/_global/main.js")) {
            $jsIndexFile = "import \"./{$module->getSlug()}/_global/main.js\";\n" . $jsIndexFile;
        }
        self::copyFolderRecursive($jsFolder, self::RESOURCE_CACHE_PATH .'/js/'. $module->getSlug());

        /*
         * Assets
         */
        $assetsFolder = $absoluteModuleResourcePath . '/public/assets';
        $assetsTargetFolder = self::PUBLIC_ASSET_PATH . '/' . $module->getSlug();
        self::copyFolderRecursive($assetsFolder, $assetsTargetFolder);
    }




    public static function copyFolderRecursive(string $source, string $dest) {

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
                if(!file_exists($dest . '/' . $iterator->getSubPathName())) {
                    FileEditor::createFolder($dest . '/' . $iterator->getSubPathName());
                }
            } else {
                copy($item, $dest . '/' . $iterator->getSubPathName());
            }
        }

    }



}