<?php

namespace webu\system\Core\Helper\FrameworkHelper;

use RecursiveIteratorIterator;
use webu\system\Core\Base\Custom\FileCrawler;
use webu\system\Core\Base\Custom\FileEditor;
use webu\system\Core\Contents\Modules\Module;
use webu\system\Core\Contents\Modules\ModuleCollection;



class ResourceCollector {

    const RESOURCE_CACHE_FOLDER = ROOT . CACHE_DIR . "\\private\\resources";

    const SCSS_ENTRY_POINT = self::RESOURCE_CACHE_FOLDER . "\\scss\\index.scss";

    public static function isGatheringNeeded() : bool {
        return file_exists(self::RESOURCE_CACHE_FOLDER);
    }

    public function gatherModuleData(ModuleCollection $moduleCollection) {
        dump($moduleCollection);

        $fileCrawler = new FileCrawler();

        //scss
        $scssIndexFile = "/* Index File - generated automatically*/" . PHP_EOL . PHP_EOL;
        /** @var Module $module */
        foreach($moduleCollection->getModuleList() as $module) {
            $scssFolder = $module->getResourcePath() . "\\public\\scss";
            if(file_exists($scssFolder . "\\base.scss")) {
                $scssIndexFile .= "@import \"{$module->getName()}/base\";";
            }
            self::copyFolderRecursive($scssFolder, dirname(self::SCSS_ENTRY_POINT) . "\\" . $module->getName());
        }
        FileEditor::createFile(self::SCSS_ENTRY_POINT, $scssIndexFile);


    }





    public static function copyFolderRecursive(string $source, string $dest) {

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
                mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            } else {
                copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            }
        }

    }







}