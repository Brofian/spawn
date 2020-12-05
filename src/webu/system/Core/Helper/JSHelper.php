<?php

namespace webu\system\Core\Helper;


use webu\system\Core\Base\Custom\FileCrawler;
use webu\system\Core\Base\Custom\FileEditor;

class JSHelper
{

    /** @var array */
    private $scriptFolders = [];
    /** @var array */
    private $staticScript = [];
    /** @var string */
    private $cacheFile = ROOT . '/var/cache/js/all.js';
    /** @var bool */
    private $alwaysReload = false;


    public function __construct()
    {
        $this->alwaysReload = (MODE == 'dev');
        $this->addScriptFolder(ROOT . '/src/Resources/public/js');
    }

    public function unifyJS()
    {
        if ($this->cacheExists() && !$this->alwaysReload) {
            //File already exists and no force-reload
            return;
        }


        $unifiedJS = $this->unify();
        $this->createCacheFile($unifiedJS);
    }


    /**
     * @param string $scriptFolder
     */
    public function addScriptFolder(string $scriptFolder)
    {
        $this->scriptFolders[] = $scriptFolder;
    }

    public function addStaticScript(string $staticScript)
    {
        $this->staticScript[] = $staticScript;
    }


    /**
     * @return string
     */
    private function unify(): string
    {

        $js = join(PHP_EOL, $this->staticScript);

        foreach ($this->scriptFolders as $folder) {
            $contents = $this->gatherFilesFromFolder($folder);
            foreach ($contents as $content) {
                $js .= $content;
            }
        }

        return $js;
    }

    /**
     * @param string $folder
     * @return array
     */
    private function gatherFilesFromFolder(string $folder): array
    {
        $fileCrawler = new FileCrawler();
        $infos = $fileCrawler->searchInfos($folder,
            function ($content, &$currentResults, $filename, $currentFilePath) {
                $pathinfo = pathinfo($filename);
                if ($pathinfo['extension'] == 'js' || $pathinfo['extension'] == 'plugin.js') {
                    $text = $content . PHP_EOL;
                    if(MODE == 'dev') {
                        $text = '//From File: ' . $filename . PHP_EOL . $text;
                    }

                    $currentResults[] = $text;
                }
            }
        );

        return $infos ?? [];
    }


    /**
     * @param string $content
     */
    private function createCacheFile(string $content)
    {
        FileEditor::insert($this->cacheFile, $content);
    }

    /**
     * @return bool
     */
    private function cacheExists(): bool
    {
        return file_exists($this->cacheFile);
    }


}