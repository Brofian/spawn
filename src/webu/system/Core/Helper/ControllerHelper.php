<?php

namespace webu\system\Core\Helper;

use webu\system\Core\Base\Custom\FileCrawler;

class ControllerHelper
{

    /** @var array $controllers */
    private $controllers = array();


    public function __construct()
    {
        $this->controllers = $this->loadControllers();
    }

    /**
     * @param $search
     * @return mixed|bool
     */
    public function getControllerByAlias($search)
    {
        $search = strtolower(trim($search));

        /** @var array $controllers */
        foreach($this->controllers as $alias => $controller) {

            if ($alias === $search) {
                return new $controller();
            }

        }

        return false;
    }


    public function getControllers(): array
    {
        return $this->controllers;
    }


    private function loadControllers() {
        $controllerDir = ROOT .  "\\src\\Controllers";

        if(is_dir($controllerDir) == false) {
            //Directory does not exist
            return [];
        }

        $filecrawler = new FileCrawler();
        $ergs = $filecrawler->searchInfos(
            $controllerDir,
            function($fileContent, &$ergs, $filename, $path, $relativePath) {

                $regex = '/class (.*) extends Controller/m';
                preg_match($regex, $fileContent, $matches);
                if(sizeof($matches) < 2) {
                    return;
                }
                $class = $matches[1];

                $regex = '/namespace (.*);/m';
                preg_match($regex, $fileContent, $matches);
                if(sizeof($matches) < 2) {
                    return;
                }
                $namespace = $matches[1];

                /** @var String $full_classname */
                $full_classname = $namespace . "\\" . $class;

                $alias = $full_classname::getControllerAlias();
                $ergs[$alias] = $full_classname;

            },
            0
        );

        return $ergs;
    }



    private function getNamespaceFromFile($fileContent)
    {
        $namespaceMatches = array();
        preg_match('/namespace (.*);/m', $fileContent, $namespaceMatches);
        if (sizeof($namespaceMatches) >= 2) {
            //get the namespace
            return $namespaceMatches[1];
        }
        return false;
    }

    /**
     * @return ControllerStorage
     */
    public function getCurrentController() : ControllerStorage{
        return $this->currentController;
    }

}