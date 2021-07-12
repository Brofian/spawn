<?php declare(strict_types=1);

namespace spawn\system\Core\Base\Database\DBAL\Aggregation;

abstract class AbstractAggregation {

    abstract public function getAggregation() : string;

}