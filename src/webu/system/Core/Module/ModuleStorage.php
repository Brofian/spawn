<?php

namespace webu\system\Core\Module;

/**
 * Class ModuleStorage -> Stores Modules in a simple, readable and usable form
 * @package webu\system\Core\Module
 */
class ModuleStorage {

    private $basepath = '';
    private $name = '';
    private $namespace = '';
    private $callableName = '';
    private $absolutePath = '';

    public function getInstance() {
        $callable = $this->callableName;
        /** @var Module $callable */
        return new $callable();
    }

    public function getAbsolutePath() : string {
        return $this->absolutePath;
    }

    public function getName() {
        return $this->name;
    }
    public function getCallableName() {
        return $this->callableName;
    }
    public function getNamespace() {
        return $this->namespace;
    }
    public function getBasepath() {
        return $this->basepath;
    }


    public function setAbsolutePath(string $absolutepath) {
        $this->absolutePath = $absolutepath;
    }
    public function setBasePath(string $basepath) {
        $this->basepath = $basepath;
    }
    public function setName(string $name) {
        $this->name = $name;
    }
    public function setNamespace(string $namespace) {
        $this->namespace = $namespace;
    }
    public function setCallableName(string $callableName) {
        $this->callableName = $callableName;
    }

}