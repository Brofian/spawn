<?php

namespace webu\system\Core\Contents\Modules;

use webu\system\Core\Helper\URIHelper;

class Module {


    /** @var array */
    private $informations = array();

    /** @var string  */
    private $moduleName;

    /** @var array  */
    private $moduleControllers = array();

    /** @var string  */
    private $basePath;

    /** @var string */
    private $resourcePath;

    /** @var string */
    private $resourceWeight;

    public function __construct(string $moduleName)
    {
        $this->moduleName = $moduleName;
    }


    /*
     *
     * SETTER
     *
     */

    /**
     * @param ModuleController $moduleController
     */
    public function addModuleController(ModuleController $moduleController) {
        $this->moduleControllers[$moduleController->getId()] = $moduleController;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function setInformation(string $key, string $value) {
        $this->informations[$key] = $value;
    }

    /**
     * @param string $basePath
     */
    public function setBasePath(string $basePath) {
        $this->basePath = $basePath;
    }


    /**
     * @param string $resourcePath
     */
    public function setResourcePath(string $resourcePath) {
        $this->resourcePath = $resourcePath;
    }


    /**
     * @param string $resourcePath
     */
    public function setResourceWeight(string $resourceWeight) {
        $this->resourceWeight = $resourceWeight;
    }


    /*
     *
     * GETTER
     *
     */

    /**
     * @return string
     */
    public function getName() {
        return $this->moduleName;
    }

    /**
     * @param string $key
     * @return array|mixed
     */
    public function getInformation(string $key = "") {
        if($key != "") {
            return $this->informations[$key];
        }
        else {
            return $this->informations;
        }
    }


    /**
     * @return array
     */
    public function getModuleControllers() {
        return $this->moduleControllers;
    }

    /**
     * @return string
     */
    public function getBasePath() : string {
        return $this->basePath;
    }


    /**
     * @return string
     */
    public function getResourcePath() : string {
        return URIHelper::joinPaths($this->basePath, $this->resourcePath);
    }

    /**
     * @return string
     */
    public function getResourceWeight() : string {
        return $this->resourceWeight;
    }

}