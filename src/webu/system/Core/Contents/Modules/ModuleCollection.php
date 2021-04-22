<?php

namespace webu\system\Core\Contents\Modules;

class ModuleCollection {

    const DEFAULT_NAMESPACE = "default";

    /** @var array  */
    private $modules = array();


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
            if(!$module->isActive()) continue;

            $namespace = ($module->getResourceNamespace() == "") ? "DEFAULT_NAMESPACE" : $module->getResourceNamespace();
            $namespace_raw = $module->getResourceNamespaceRaw();

            if(!in_array($namespace, $namespaces)) {
                $namespaces[$namespace_raw] = $namespace;
            }
        }

        return $namespaces;
    }


    /**
     * @param array $moduleList
     */
    public static function sortModulesByWeight(array &$moduleList) {
        usort($moduleList, function($a, $b) {
            /** @var $a Module */
            /** @var $b Module */

            if($a->getResourceWeight() < $b->getResourceWeight()) return -1;
            else if($a->getResourceWeight() > $b->getResourceWeight()) return 1;
            else return 0;
        });
    }

}