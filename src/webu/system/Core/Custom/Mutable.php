<?php

namespace webu\system\Core\Custom;

abstract class Mutable {

    public function set(string $key, $value, bool $allowOverride = true) {
        if($allowOverride || !isset($this->$key)) {
            $this->$key = $value;
        }
    }

    public function get(string $key) {
        if(isset($this->$key)) {
            return $this->$key;
        }
        else {
            return null;
        }
    }


}