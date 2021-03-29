<?php


namespace webu\system\Core\Base\Database\Query;


use webu\system\Core\Base\Database\DatabaseConnection;
use webu\system\Core\Base\Database\Query\Types\QueryDelete;
use webu\system\Core\Base\Database\Query\Types\QueryInsert;
use webu\system\Core\Base\Database\Query\Types\QuerySelect;
use webu\system\Core\Base\Database\Query\Types\QueryUpdate;

class QueryCondition
{
    /** @var array  */
    private $conditions = [];

    private $necessary = true;

    public function __construct($necessary = true)
    {
        $this->necessary = $necessary;
    }

    public function addCondition(string $column, $value, bool $isOr = false, bool $isNot = false) {
        $this->conditions[] = [
            'column' => $column,
            'value' => $value,
            'isOr' => $isOr,
            'isNot' => $isNot
        ];
    }

    public function and(string $column, $value) {
        $this->addCondition($column, $value,false,false);
    }

    public function or(string $column, $value) {
        $this->addCondition($column, $value,true,false);
    }

    public function andNot(string $column, $value) {
        $this->addCondition($column, $value,false,true);
    }

    public function orNot(string $column, $value) {
        $this->addCondition($column, $value,true,true);
    }

    /**
     * @return array
     */
    public function getConditions() : array {
        return $this->conditions;
    }

    /**
     * @return bool
     */
    public function isNecessary(): bool
    {
        return $this->necessary;
    }




}