<?php

namespace webu\system\Core\Base\Database\DBAL\Aggregation;

abstract class AbstractAggregation {

    abstract public function getAggregation() : string;

}