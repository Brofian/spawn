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


}