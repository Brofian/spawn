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

    const SCSS_ENTRY_POINT = self::RESOURCE_CACHE_FOLDER . "/scss/index.scss";
    const JS_ENTRY_POINT   = self::RESOURCE_CACHE_FOLDER . "/js/index.js";

    public static function isGatheringNeeded() : bool {
        return file_exists(self::RESOURCE_CACHE_FOLDER);
    }

    public function gatherModuleData(ModuleCollection $moduleCollection) {

        //scss
        $scssIndexFile = "/* Index File - generated automatically*/" . PHP_EOL . PHP_EOL;
        $jsIndexFile = "/* Index File - generated automatically*/" . PHP_EOL . PHP_EOL;

        /** @var Module $module */
        foreach($moduleCollection->getModuleList() as $module) {

            /*
             * SCSS
             */
            $scssFolder = $module->getResourcePath() . "/public/scss";
            if(file_exists($scssFolder . "/base.scss")) {
                $scssIndexFile .= "@import \"{$module->getName()}/base\";\n";
            }
            self::copyFolderRecursive($scssFolder, dirname(self::SCSS_ENTRY_POINT) . "/" . $module->getName());


            /*
             * Javascript
             */
            $jsFolder = $module->getResourcePath() . "/public/js";
            if(file_exists($jsFolder . "/main.js")) {
                $jsIndexFile .= "import \"./{$module->getName()}/main.js\";\n";
            }
            self::copyFolderRecursive($jsFolder, dirname(self::JS_ENTRY_POINT) . "/" . $module->getName());
        }

        FileEditor::createFile(self::SCSS_ENTRY_POINT, $scssIndexFile);
        FileEditor::createFile(self::JS_ENTRY_POINT, $jsIndexFile);




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