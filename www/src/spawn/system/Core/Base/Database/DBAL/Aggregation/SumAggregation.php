<?php

namespace spawn\system\Core\Base\Database\DBAL\Aggregation;

class SumAggregation extends AbstractAggregation {

    protected string $values = '*';
    protected ?string $alias = null;


    public function __construct(string $values = '*', ?string $alias = null)
    {
        $this->values = $values;
        $this->alias = null;
    }

    public function getAggregation(): string
    {
        $sql = "SUM($this->values)";
        if($this->alias != null) {
            $sql .= "AS $this->alias";
        }
        return $sql;    }
}