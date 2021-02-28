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
    public function getNamespaceList() : array {
        $namespaces = array();

        /** @var Module $module */
        foreach($this->getModuleList() as $module) {
            $namespace = ($module->getResourceNamespace() == "") ? "default" : $module->getResourceNamespace();

            if(!in_array($namespace, $namespaces)) {
                $namespaces[] = $namespace;
            }
        }

        return $namespaces;
    }


}