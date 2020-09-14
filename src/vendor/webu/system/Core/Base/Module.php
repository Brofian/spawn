<?php

namespace webu\system\Core\Base;

class Module
{

    private $path = '';
    private $basepath = '';
    private $classname = '';
    private $classname_full = '';
    private $namespace = '';
    /**
     * The Alias is used to determine, if the controller is called
     * @var string
     */
    private $alias = '';


    public function __construct()
    {
    }


    /**
     * Returns the controller of this module
     * @return mixed
     */
    public function getModuleController()
    {
        $moduleController = new $this->classname_full();
        return $moduleController;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getBasepath(): string
    {
        return $this->basepath;
    }

    public function getClassname(): string
    {
        return $this->classname;
    }

    public function getFullClassname(): string
    {
        return $this->classname_full;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }


    /**
     * @param string $path
     * @return Module
     */
    public function setPath(string $path): Module
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @param string $basepath
     * @return Module
     */
    public function setBasepath(string $basepath): Module
    {
        $this->basepath = $basepath;
        return $this;
    }

    /**
     * @param string $classname
     * @return Module
     */
    public function setClassname(string $classname): Module
    {
        $this->classname = $classname;
        return $this;
    }

    /**
     * @param string $classname_full
     * @return Module
     */
    public function setFullClassname(string $classname_full): Module
    {
        $this->classname_full = $classname_full;
        return $this;
    }

    /**
     * @param string $namespace
     * @return Module
     */
    public function setNamespace(string $namespace): Module
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @param string $alias
     * @return Module
     */
    public function setAlias(string $alias): Module
    {
        $this->alias = $alias;
        return $this;
    }

}