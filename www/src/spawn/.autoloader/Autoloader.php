<?php declare(strict_types=1);

namespace spawn\autoloader;

use spawnCore\Custom\Gadgets\FileCrawler;
use spawnCore\Custom\Gadgets\FileEditor;
use spawnCore\Custom\Gadgets\URIHelper;
use spawnCore\Custom\Throwables\ClassNotFoundException;

class Autoloader
{
    const FILENAME =  ROOT . CACHE_DIR . '/private/generated/autoloader/classpaths.php';

    public array $classpaths = array();
    public bool $alwaysReload = false;

    public function __construct()
    {
        $this->alwaysReload = (MODE == 'dev');

        //load FileEditor
        require_once(ROOT . '/vendor/spawn/app/src/Spawn/Custom/Gadgets/FileEditor.php');
        require_once(ROOT . '/vendor/spawn/app/src/Spawn/Custom/Gadgets/URIHelper.php');
    }

    //the autoload function
    public function autoload($className = null): bool
    {
        if (!$className) return false;

        $filename = self::FILENAME;
        $filename = URIHelper::pathifie($filename, "/");

        $wasFileReloaded = false;

        //include classpaths. generate them if necessary
        if(sizeof($this->classpaths) == 0 && (file_exists($filename) && !$this->alwaysReload)) {
            $this->classpaths = include($filename);
        }
        else if(sizeof($this->classpaths) == 0 && (!file_exists($filename) || $this->alwaysReload)) {
            $this->createPathsFile($filename);
            $wasFileReloaded = true;
            $this->classpaths = include($filename);
        }

        //check if the file is listed and existing, or else recreate the file, if not already donw
        if (isset($this->classpaths[$className]) && is_file(ROOT . $this->classpaths[$className])) {

            $path = ROOT . $this->classpaths[$className];

            //if the class exists, load the file
            require_once($path);
            return true;
        } else {
            //if the the classPaths are empty, try load from the file

            if(!$wasFileReloaded) {
                $this->createPathsFile($filename);
            }

            //include the existing or generated file
            $this->classpaths = include($filename);

            if(!$this->classpaths || !is_array($this->classpaths)) {
                $this->classpaths = [];
            }


            //check if the classname is now available
            if (isset($this->classpaths[$className]) && is_file(ROOT . $this->classpaths[$className])) {

                $path = ROOT . $this->classpaths[$className];

                //if the class exists, load the file
                require_once($path);
                return true;
            }

        }


        // Throw Error
        throw new ClassNotFoundException((string)$className);
    }


    private function createPathsFile($fileName)
    {
        //load all classes recursivly
        require_once(ROOT . "/vendor/spawn/app/src/Spawn/Custom/Gadgets/FileCrawler.php");


        $crawl = function($root) {
            $crawler = new FileCrawler();
            $crawler->addIgnoredDirName('cache');
            $crawler->addIgnoredDirName('vendor');

            $data = $crawler->searchInfos(
                $root,
                function ($fileContent, &$ergs, $filename, $path, $relativePath) {

                    $pathInfo = pathinfo($filename);
                    if (isset($pathInfo['extension']) && $pathInfo['extension'] != 'php') {
                        return;
                    }

                    $namespaceMatches = array();
                    preg_match('/namespace (.*);/m', $fileContent, $namespaceMatches);

                    if (sizeof($namespaceMatches) >= 2) {
                        //get the namespace
                        $namespace = $namespaceMatches[1];
                        //append the classname to the namespace
                        $strPos = (int)(strrpos($filename, '.'));
                        $namespace .= "\\" . substr($filename, 0, $strPos);


                        //save in the classes array
                        $ergs[$namespace] = $relativePath . $filename;
                    }
                }
            );

            return $data;
        };


        $data = $crawl(ROOT);



        //create the file
        $classPathList = "<?php \n \$classPathsCache = [ \n";
        foreach ($data as $index => $string) {
            $classPathList .= "'" . $index . "'=>'" . $string . "',\n";
        }
        $classPathList .= "];\n";
        $classPathList .= "\n return \$classPathsCache;";

        FileEditor::insert($fileName, $classPathList);
    }

}