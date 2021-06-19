<?php

namespace webu\system\Core\Base\Database\DBAL\Aggregation;

class CountAggregation extends AbstractAggregation {

    protected string $values = '*';
    protected ?string $alias = null;

    public function __construct(string $values = '*', ?string $alias = null)
    {
        $this->values = $values;
        $this->alias = $alias;
    }

    public function getAggregation(): string
    {
        $sql = "COUNT($this->values)";
        if($this->alias != null) {
            $sql .= "AS $this->alias";
        }
        return $sql;
    }
}