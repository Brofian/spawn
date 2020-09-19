<?php

namespace webu\system\Core\Module;

class ControllerStorage
{
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
    public function getInstance()
    {
        $controller = $this->classname_full;
        return new $controller();
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
     * @param string $classname
     * @return ControllerStorage
     */
    public function setClassname(string $classname): ControllerStorage
    {
        $this->classname = $classname;
        return $this;
    }

    /**
     * @param string $classname_full
     * @return ControllerStorage
     */
    public function setFullClassname(string $classname_full): ControllerStorage
    {
        $this->classname_full = $classname_full;
        return $this;
    }

    /**
     * @param string $namespace
     * @return ControllerStorage
     */
    public function setNamespace(string $namespace): ControllerStorage
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @param string $alias
     * @return ControllerStorage
     */
    public function setAlias(string $alias): ControllerStorage
    {
        $this->alias = $alias;
        return $this;
    }

}