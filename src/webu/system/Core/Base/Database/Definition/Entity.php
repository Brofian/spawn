<?php

namespace webu\system\Core\Base\Database\Definition;

use webu\system\Core\Contents\Collection\Collection;

abstract class Entity {

    public final function getEntityClass() : string {
        return self::class;
    }

}