<?php declare(strict_types=1);

namespace spawn\system\Core\Custom;

abstract class Mutable {

    public function set(string $key, $value, bool $allowOverride = true) {
        if($allowOverride || !isset($this->$key)) {
            $this->$key = $value;
        }
    }

    public function get(string $key) {
        if($this->has($key)) {
            return $this->$key;
        }
        else {
            return null;
        }
    }

    public function has(string $key): bool {
        return isset($this->$key);
    }


}