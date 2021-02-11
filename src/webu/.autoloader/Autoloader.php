<?php

namespace webu\autoloader;

use webu\system\Core\Base\Custom\FileCrawler;
use webu\system\Core\Base\Custom\FileEditor;

class Autoloader
{

    public $classpaths = array();
    public $alwaysReload = false;

    public function __construct()
    {
        $this->alwaysReload = (MODE == 'dev');

        //load FileEditor
        require_once(ROOT . '/src/webu/system/Core/Custom/FileEditor.php');
    }

    //the autoload function
    public function autoload($className = false): bool
    {
        if (!$className) return false;

        if (isset($this->classpaths[$className])) {
            //if the class exists, load the file
            require_once($this->classpaths[$className]);
            return true;
        } else if (sizeof($this->classpaths) == 0) {
            //if the the classPaths are empty, try load from the file

            $folderName = ROOT . '\\var\\generated\\cache\\';
            $fileName = $folderName . 'classpaths.php';

            //create directory if needed
            FileEditor::createFolder($folderName);

            //create file if needed
            if (!file_exists($fileName) || $this->alwaysReload) {
                $this->createPathsFile($fileName);
            }

            //include the existing or generated file
            include($fileName);

            if(!isset($classPathsCache)) {
                $classPathsCache = [];
            }
            $this->classpaths = $classPathsCache;


            //check if the classname is now available
            if (isset($this->classpaths[$className])) {
                //if the class exists, load the file
                require_once($this->classpaths[$className]);
                return true;
            }

        }
        return false;
    }


    private function createPathsFile($fileName)
    {
        //load all classes recursivly
        require_once(ROOT . "/src/webu/system/Core/Custom/FileCrawler.php");


        $crawl = function($root) {
            $crawler = new FileCrawler();
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
                        $namespace .= "\\" . substr($filename, 0, strrpos($filename, '.'));

                        //save in the classes array
                        $ergs[$namespace] = $path;
                    }
                }
            );

            return $data;
        };


        $dataSrc = $crawl(ROOT . "\\src");
        $dataBin = $crawl(ROOT . "\\bin");


        $data = array_merge($dataSrc, $dataBin);


        //create the file
        $classPathList = "<?php \n \$classPathsCache = [ \n";
        foreach ($data as $index => $string) {
            $classPathList .= "'" . $index . "'=>'" . $string . "',\n";
        }
        $classPathList .= "];\n";

        FileEditor::insert($fileName, $classPathList);
    }


}





