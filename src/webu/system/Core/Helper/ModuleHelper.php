<?php

namespace webu\system\Core\Helper;

use webu\system\Core\Base\Controller\Controller;
use webu\system\Core\Base\Custom\FileCrawler;
use webu\system\Core\Custom\Debugger;
use webu\system\Core\Module\ControllerStorage;
use webu\system\Core\Module\Module;
use webu\system\Core\Module\ModuleStorage;

class ModuleHelper
{

    /** @var array $modules */
    private $modules = array();
    /** @var array $controllers */
    private $controllers = array();

    /** @var ModuleStorage $currentModule */
    private $currentModule = null;
    /** @var ControllerStorage $currentController */
    private $currentController = null;


    /**W
     * @param $search
     * @return mixed|bool
     */
    public function getControllerByAlias($search)
    {
        $search = strtolower(trim($search));

        /** @var array $controllers */
        foreach($this->controllers as $module => $controllers) {

            /** @var ControllerStorage $controller */
            foreach ($controllers as $controller) {
                $alias = strtolower($controller->getAlias());

                if ($alias === $search) {
                    $this->currentController = $controller;
                    return $controller->getInstance();
                }
            }

        }

        return false;
    }

    public function getModuleCount(): int
    {
        return sizeof($this->modules);
    }

    public function getModules(): array
    {
        return $this->modules;
    }

    public function getControllers(): array
    {
        return $this->controllers;
    }


    public function loadModules()
    {

        $this->modules = array();

        $moduleStorages = $this->loadModuleClasses();

        $idTracker = 0;
        //get controllers from modules
        /** @var ModuleStorage $moduleStorage */
        foreach ($moduleStorages as $moduleStorage) {

            /** @var array $controllerStorages */
            $controllerStorages = $this->loadControllersFromModule($moduleStorage);

            if(sizeof($controllerStorages) > 0) {
                $moduleName = $moduleStorage->getName();
                $this->controllers[$moduleName] = array();

                foreach($controllerStorages as $controllerStorage) {
                    $this->controllers[$moduleName][] = $controllerStorage;
                }

            }

            $this->modules[] = $moduleStorage;
        }

    }


    private function loadControllersFromModule(ModuleStorage $moduleStorage) {
        $modulePath = $moduleStorage->getBasePath();

        $controllerDir = $modulePath . '\\Controllers';

        if(is_dir($controllerDir) == false) {
            //Module has no controllers
            return [];
        }

        $filecrawler = new FileCrawler();
        $ergs = $filecrawler->searchInfos(
            $controllerDir,
            function($fileContent, &$ergs, $filename, $path) {

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

                $full_classname = $namespace . "\\" . $class;

                /** @var Controller $full_classname */
                $alias = $full_classname::getControllerAlias();

                $controllerStorage = new ControllerStorage();
                $controllerStorage  ->setClassname($class)
                                    ->setNamespace($namespace)
                                    ->setAlias($alias)
                                    ->setFullClassname($full_classname);

                $ergs[] = $controllerStorage;

            },
            0
        );

        /** @var ControllerStorage $erg */
        foreach($ergs as $erg) {
            $erg->setModule($moduleStorage);
        }


        return $ergs;
    }

    private function loadModuleClasses(): array
    {
        $modulesFolder = RELROOT . '\\' . 'src\\modules';

        $crawler = new FileCrawler();
        $modules = $crawler->searchInfos(
            $modulesFolder,
            function ($fileContent, &$ergs, $filename, $path) {

                $regex = '/class (.*) extends Module/m';
                $matches = array();
                preg_match($regex, $fileContent, $matches);


                //check if the class extends the controller
                if (sizeof($matches) < 2) {
                    return;
                }

                //check if the class is in a folder with the same name
                $class = $matches[1];
                $regex = '/((.*)\\\\' . $class . ')\\\\' . $filename . '/m';
                preg_match($regex, $path, $matches);

                if (sizeof($matches) < 3) {
                    return;
                }

                $namespace = $this->getNamespaceFromFile($fileContent);
                $fullClassname = $namespace . '\\' . $class;

                $module = new ModuleStorage();
                $module->setNamespace($namespace);
                $module->setName($class);
                $module->setBasePath(dirname($path));
                $module->setAbsolutePath(str_replace(RELROOT, ROOT, dirname($path)));

                $ergs[] = $module;

            },
            1
        );

        return $modules;
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