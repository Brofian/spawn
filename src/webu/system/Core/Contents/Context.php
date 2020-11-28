<?php

namespace webu\system\Core\Contents;

class Context {

    private $context = array();

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

}