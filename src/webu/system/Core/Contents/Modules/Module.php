<?php

namespace webu\system\Core\Contents\Modules;

use webu\system\Core\Helper\URIHelper;

class Module {

    /** @var string  */
    private $id = "";
    /** @var bool  */
    private $active = false;
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
    /** @var string */
    private $resourceNamespace;
    /** @var string */
    private $resourceNamespaceRaw;
    /** @var array */
    private $databaseTableClasses = array();
    /** @var array  */
    private $usingNamespaces = array();
    /** @var string */
    private $slug;

    /**
     * Module constructor.
     * @param string $moduleName
     */
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
     * @param string $tableClass
     */
    public function addDatabaseTableClass(string $tableClass) {
        $this->databaseTableClasses[] = $tableClass;
    }

    /**
     * @param ModuleController $moduleController
     */
    public function addModuleController(ModuleController $moduleController) {
        $this->moduleControllers[] = $moduleController;
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

    /**
     * @param string $basePath
     */
    public function setResourceNamespace(string $resourceNamespace) {
        $this->resourceNamespace = $resourceNamespace;
    }

    /**
     * @param string $basePath
     */
    public function setResourceNamespaceRaw(string $resourceNamespaceRaw) {
        $this->resourceNamespaceRaw = $resourceNamespaceRaw;
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
            if(isset($this->informations[$key])) {
                return $this->informations[$key];
            }
            else {
                return "";
            }
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
    public function getRelativeResourcePath() : string {
        return $this->resourcePath;
    }

    /**
     * @return string
     */
    public function getResourceWeight() : string {
        return $this->resourceWeight;
    }

    /**
     * @return string
     */
    public function getResourceNamespace() : string {
        return $this->resourceNamespace;
    }

    /**
     * @return string
     */
    public function getResourceNamespaceRaw() : string {
        return $this->resourceNamespaceRaw;
    }


    public function getDatabaseTableClasses() : array {
        return $this->databaseTableClasses;
    }

    /**
     * @return array
     */
    public function getUsingNamespaces(): array
    {
        return $this->usingNamespaces;
    }

    /**
     * @param array $usingNamespaces
     */
    public function setUsingNamespaces(array $usingNamespaces): void
    {
        $this->usingNamespaces = $usingNamespaces;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $moduleSlug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }





}