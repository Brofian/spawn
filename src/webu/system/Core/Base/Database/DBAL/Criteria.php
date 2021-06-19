<?php

namespace webu\system\Core\Base\Database\DBAL;

use webu\system\Core\Base\Database\DBAL\Aggregation\AbstractAggregation;
use webu\system\Core\Base\Database\DBAL\Filter\AbstractFilter;
use webu\system\Core\Base\Database\DBAL\Filter\AndFilter;
use webu\system\Core\Base\Database\DBAL\Join\AbstractJoin;

class Criteria {

    /** @var AbstractFilter[]  */
    protected array $filter = array();
    /** @var AbstractAggregation[]  */
    protected array $aggregations = array();
    /** @var AbstractJoin[] */
    protected array $joins = array();
    protected string $table;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function addFilter(AbstractFilter $filter): self {
        $this->filter[] = $filter;
        return $this;
    }

    public function addAggregation(AbstractAggregation $aggregation): self {
        $this->aggregations[] = $aggregation;
        return  $this;
    }

    public function addJoin(AbstractJoin $join) : self {
        $this->joins[] = $join;
        return $this;
    }

    public function getSQL() : string {
        $sql = 'SELECT *';

        //aggregations
        foreach($this->aggregations as $aggregation) {
            $sql .= ','.$aggregation->getAggregation().' ';
        }

        $sql .= "FROM $this->table ";

        //join
        $sql .= $this->getJoin();

        $sql .= $this->getWhere();

        return $sql;
    }

    public function getJoin() : string {
        $sql = "";
        foreach($this->joins as $join) {
            $sql .= $join->getJoin() . " ";
        }
        return "  $sql  ";
    }

    public function getWhere(): string {
        $sql = "";
        if(count($this->filter)) {
            $sql .= ' WHERE ';

            $andFilter = new AndFilter(...$this->filter);
            $sql .= $andFilter->getFilter();
        }
        return "  $sql  ";
    }


    public function getValues(): array
    {
        $values = array();
        foreach($this->filter as $filter) {
            $values = array_merge($values, $filter->getValues());
        }
        return $values;
    }


}