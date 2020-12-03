<?php

namespace webu\system\Core\Contents;

class Context {

    /** @var array $context */
    private $context = array();
    /** @var bool $isBackendContext */
    private $isBackendContext = false;


    public function set(string $name, $variable) {
        $this->context[$name] = $variable;
    }

    public function multiSet(array $entries) {
        foreach($entries as $name => $variable) {
            $this->context[$name] = $variable;
        }
    }

    public function getContext() {
        return $this->context;
    }

    public function setBackendContext() {
        $this->isBackendContext = true;
    }

    public function getBackendContext() {
        return $this->isBackendContext;
    }

}