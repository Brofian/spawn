<?php

namespace webu\system\Core\Helper;

use webu\system\Core\Base\Custom\FileCrawler;
use webu\system\Core\Base\Module;

class ModuleHelper {

    /** @var array $modules */
    private $modules = array();


    /**
     * @param $search
     * @return mixed|bool
     */
    public function getModuleByAlias($search) {
        $search = strtolower(trim($search));

        /** @var Module $module */
        foreach($this->modules as $module) {
            $alias = $module->getAlias();
            if($alias === $search) {
                return $module->getModuleController();
            }
        }
        return false;
    }

    public function getModuleCount() : int {
        return sizeof($this->modules);
    }

    public function getModules() : array {
        return $this->modules;
    }

    public function loadModules() {

        $this->modules = array();

        $moduleClasses = $this->loadModuleClasses();
        foreach($moduleClasses as $moduleClass) {
            $module = new Module();
            $module->setBasepath($moduleClass['basepath'])
                    ->setPath($moduleClass['path'])
                    ->setClassname($moduleClass['classname'])
                    ->setFullClassname($moduleClass['classname_full'])
                    ->setAlias($moduleClass['alias'])
                    ->setNamespace($moduleClass['namespace']);

            $this->modules[] = $module;
        }

    }



    private function loadModuleClasses() : array {
        $modulesFolder = RELROOT. '\\' . 'src\\modules';


        $crawler = new FileCrawler();
        $modules = $crawler->searchInfos(
            $modulesFolder,
            function($fileContent, &$ergs, $filename, $path) {


                $regex = '/class (.*) extends Controller/m';
                $matches = array();
                preg_match($regex, $fileContent, $matches);

                //check if the class extends the controller
                if(sizeof($matches) < 2) {
                    return;
                }

                //check if the class is in a folder with the same name
                $class = $matches[1];
                $regex = '/((.*)\\\\'.$class.')\\\\'.$filename.'/m';
                preg_match($regex, $path, $matches);

                if(sizeof($matches) < 3) {
                    return;
                }

                $namespace = $this->getNamespaceFromFile($fileContent);
                $fullClassname = $namespace . '\\' .  $class;
                $alias = $fullClassname::getControllerAlias();
                $alias = strtolower(    trim($alias)  );


                if(isset($ergs[$alias])) {
                    throw new \Exception("Duplicated Module!");
                }



                $ergs[$alias] = [
                    'path' => $path,
                    'basepath' => $matches[1],
                    'classname' => $class,
                    'classname_full' => $fullClassname,
                    'namespace' => $namespace,
                    'alias' => $alias
                ];

            },
            1
        );

        return $modules;
    }


    private function getNamespaceFromFile($fileContent) {
        $namespaceMatches = array();
        preg_match('/namespace (.*);/m', $fileContent, $namespaceMatches);
        if(sizeof($namespaceMatches) >= 2) {
            //get the namespace
            return $namespaceMatches[1];
        }
        return false;
    }




}