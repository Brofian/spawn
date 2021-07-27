<?php declare(strict_types=1);

namespace spawn\system\Core\Helper;

use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Exception\CompilerException;
use spawn\system\Core\Base\Custom\FileEditor;
use spawn\system\Core\Contents\Modules\ModuleCollection;
use spawn\system\Core\Custom\Debugger;


class ScssHelper
{

    const SCSS_FILES_PATH = ROOT . '/vendor/scssphp/scssphp/scss.inc.php';

    private $alwaysReload = false;

    private $baseVariables = array();

    public $cacheFilePath = ROOT . CACHE_DIR . '/public/css';
    public $baseFolder = ROOT . CACHE_DIR . '/private/resources/modules';
    public $baseFileName = 'scss/index.scss';


    public function __construct()
    {
        $this->alwaysReload = (MODE == 'dev');
        require_once self::SCSS_FILES_PATH;
    }



    private function compile(string $baseFile, bool $compressed = false)
    {
        $scss = new Compiler();

        //set the output style
        $outputStyle = $compressed ? \ScssPhp\ScssPhp\OutputStyle::COMPRESSED : \ScssPhp\ScssPhp\OutputStyle::EXPANDED;
        $scss->setOutputStyle($outputStyle);

        $this->registerFunctions($scss);

        $baseVariables = $this->compileBaseVariables();

        //set Base path for files
        $scss->setImportPaths([dirname($baseFile)]);

        try {
            $css = $scss->compile('
              ' . $baseVariables . '
              @import "' . basename($baseFile) . '";
            ');
        } catch (CompilerException $e) {
            $css = "";

            if (MODE == 'dev') {
                Debugger::ddump($e);
            }
        }


        return $css;
    }

    private function compileBaseVariables()
    {
        $result = "";

        foreach ($this->baseVariables as $name => $value) {
            $result .= '$' . $name . ' : "' . $value . '";' . PHP_EOL;
        }

        return $result;
    }

    public function cacheExists(): bool
    {
        return file_exists($this->cacheFilePath);
    }


    public function createCss(ModuleCollection $moduleCollection)
    {

        $baseFile = URIHelper::joinMultiplePaths($this->baseFolder, $this->baseFileName);


        $css = $this->compile($baseFile);
        $cssMinified = $this->compile($baseFile, true);


        /** @var FileEditor $fileWriter */
        $fileWriter = new FileEditor();
        $fileWriter->createFolder($this->cacheFilePath);

        $filePath = URIHelper::joinPaths($this->cacheFilePath, 'all.css');
        $fileWriter->createFile($filePath, $css);
        $minifiedFilePath = URIHelper::joinPaths($this->cacheFilePath, 'all.min.css');
        $fileWriter->createFile($minifiedFilePath, $cssMinified);


    }


    private function registerFunctions(Compiler &$scss)
    {
        //register custom scss functions
        $scss->registerFunction(
            'degToPadd',
            function ($args) {
                $deg = $args[0][1];
                $a = $args[1][1];


                $magicNumber = tan(deg2rad($deg) / 2);
                $contentWidth = $a;

                $erg = $magicNumber * $contentWidth;
                return $erg . "px";
            }
        );


        $scss->registerFunction(
            'assetURL',
            function ($args) {
                $path = $args[0][1];
                $fullpath = ROOT . 'src/Resources/public/assets/' . $path;

                $url = "url('" . $fullpath . "')";
                return $url;
            }
        );

    }


    public function setBaseVariable(string $name, string $value)
    {
        $this->baseVariables[$name] = $value;
    }
}