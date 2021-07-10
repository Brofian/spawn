<?php declare(strict_types=1);

namespace spawn\system\Core\Base\Database\DBAL\Join;

abstract class AbstractJoin {

    abstract public function getJoin() : string;

}