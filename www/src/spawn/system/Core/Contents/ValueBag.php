<?php declare(strict_types=1);

namespace spawn\system\Core\Contents;

use spawn\system\Core\Custom\Mutable;

class ValueBag extends Mutable {

    public function toArray() {
        return get_object_vars($this);
    }

}