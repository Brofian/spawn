<?php

namespace webu\system\Core\Helper;

use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Exception\CompilerException;
use webu\system\Core\Base\Custom\FileEditor;
use webu\system\Core\Custom\Debugger;
use webu\system\Core\Extensions\Scss\scss_functions;


class ScssHelper {

    private $cacheFilePath      = ROOT . '/var/cache/public/css/all.css';
    private $cacheFileMiniPath  = ROOT . '/var/cache/public/css/all.min.css';
    private $baseFolderPath     = ROOT . '/src/Resources/public/scss/Front/';
    private $baseFileName       =        'Base.scss';
    private $scssFilesPath      = ROOT . '/vendor/scssphp/scssphp/scss.inc.php';
    private $alwaysReload       = false;

    public function __construct()
    {
        $this->alwaysReload = (MODE == 'dev');
        require_once $this->scssFilesPath;
    }


    public function setBackendFilePaths() {
        $this->baseFolderPath     = ROOT . '/src/Resources/public/scss/Back/';
        $this->cacheFilePath      = ROOT . '/var/cache/public/css/backend/all.css';
        $this->cacheFileMiniPath  = ROOT . '/var/cache/public/css/backend/all.min.css';
    }

    private function compile(bool $compressed = false) {
        $scss = new Compiler();

        //set the output style
        $outputStyle = $compressed ? \ScssPhp\ScssPhp\OutputStyle::COMPRESSED : \ScssPhp\ScssPhp\OutputStyle::EXPANDED;
        $scss->setOutputStyle($outputStyle);

        $this->registerFunctions($scss);

        $baseVariables = $this->compileBaseVariables();

        //set Base path for files
        $scss->setImportPaths([$this->baseFolderPath]);



        try {
            $css = $scss->compile('
              '.$baseVariables.'
              @import "'.$this->baseFileName.'";
            ');
        } catch (CompilerException $e) {
            $css = "";

            if(MODE == 'dev') {
                Debugger::ddump($e);
            }
        }


        return $css;
    }

    private function compileBaseVariables() {
        $result = "";
        foreach(BRAND_COLORS as $name => $color) {
            $result .= '$' . $name . ' : ' . $color  . ';' . PHP_EOL;
        }
        return $result;
    }

    private function cacheExists() : bool {
        return file_exists($this->cacheFilePath);
    }


    public function createCss(bool $isBackendContext = false) {
        if($isBackendContext) {
            $this->setBackendFilePaths();
        }

        if($this->cacheExists() && !$this->alwaysReload) {
            //File already exists and no force-reload
            return;
        }



        $css = $this->compile();
        $cssMinified = $this->compile(true);


        /** @var FileEditor $fileWriter */
        $fileWriter = new FileEditor();
        $fileWriter->createFolder(dirname($this->cacheFilePath));
        $fileWriter->createFile($this->cacheFilePath, $css);
        $fileWriter->createFile($this->cacheFileMiniPath, $cssMinified);
    }


    private function registerFunctions(Compiler &$scss) {
        //register custom scss functions
        $scss->registerFunction(
            'degToPadd',
            function($args) {
                $deg = $args[0][1];
                $a = $args[1][1];



                $magicNumber = tan(deg2rad($deg)/2);
                $contentWidth = $a;

                $erg =  $magicNumber * $contentWidth;
                return $erg . "px";
            }
        );


        $scss->registerFunction(
            'assetURL',
            function($args) {
                $path = $args[0][1];
                $fullpath = ROOT . 'src/Resources/public/assets/' . $path;

                $url = "url('".$fullpath."')";
                return $url;
            }
        );

    }

}