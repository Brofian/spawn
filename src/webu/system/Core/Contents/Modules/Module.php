<?php

namespace webu\system\Core\Contents\Modules;

class Module {


    /** @var array */
    private $informations = array();

    /** @var string  */
    private $moduleName;

    /** @var array  */
    private $moduleControllers = array();

    /** @var string  */
    private $basePath;

    public function __construct(string $moduleName)
    {
        $this->moduleName = $moduleName;
    }


    /*
     *
     * SETTER
     *
     */

    public function addModuleController(ModuleController $moduleController) {
        $this->moduleControllers[$moduleController->getId()] = $moduleController;
    }

    public function setInformation(string $key, string $value) {
        $this->informations[$key] = $value;
    }

    public function setBasePath(string $basePath) {
        $this->basePath = $basePath;
    }


    /*
     *
     * GETTER
     *
     */

    public function getName() {
        return $this->moduleName;
    }


    public function getInformation(string $key = "") {
        if($key != "") {
            return $this->informations[$key];
        }
        else {
            return $this->informations;
        }
    }

    public function getModuleControllers() {
        return $this->moduleControllers;
    }

}