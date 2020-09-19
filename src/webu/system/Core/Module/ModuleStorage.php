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


    public function getInstance() {
        $callable = $this->callableName;
        /** @var Module $callable */
        return new $callable();
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

/*
// @var Module $fullClassname

$alias = $fullClassname::getControllerAlias();
$alias = strtolower(trim($alias));


if (isset($ergs[$alias])) {
    throw new \Exception("Duplicated Module! \"".$alias."\"");
}


$ergs[$alias] = [
    'path' => $path,
    'basepath' => $matches[1],
    'classname' => $class,
    'classname_full' => $fullClassname,
    'namespace' => $namespace,
    'alias' => $alias
];

*/