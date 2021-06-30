<?php declare(strict_types=1);

namespace webu\system\Core\Base\Database\DBAL\Join;

abstract class AbstractJoin {

    abstract public function getJoin() : string;

}