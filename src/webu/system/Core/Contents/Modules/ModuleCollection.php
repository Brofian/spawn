<?php

namespace webu\system\Core\Contents\Modules;

class ModuleCollection {

    private $modules = array();


    public function __construct()
    {
    }


    /**
     * @param Module $module
     */
    public function addModule(Module $module) {
        $this->modules[$module->getName()] = $module;
    }


    /*
     *
     * GETTER
     *
     */


    /**
     * @return array
     */
    public function getModuleList() {
        return $this->modules;
    }


    /**
     * @return array
     */
    public function getURIList() {

        $uris = array();

        /** @var Module $module */
        foreach($this->modules as $module) {
            /** @var ModuleController $controller */
            foreach($module->getModuleControllers() as $controller) {
                /** @var string $action */
                foreach($controller->getActions() as $action) {
                    $uris[$action] = $controller;
                }
            }
        }

        return $uris;
    }

}