<?php declare(strict_types=1);


namespace webu\system\Core\Base\Database\Query;


class QueryCondition
{
    /** @var array  */
    private $conditions = [];

    /** @var bool  */
    private $necessary = true;

    /**
     * QueryCondition constructor.
     * @param bool $necessary
     */
    public function __construct($necessary = true)
    {
        $this->necessary = $necessary;
    }


    /**
     * @param string $column
     * @param $value
     * @param bool $isOr
     * @param bool $isNot
     */
    public function addCondition(string $column, $value, bool $isOr = false, bool $isNot = false) {
        $this->conditions[] = [
            'column' => $column,
            'value' => $value,
            'isOr' => $isOr,
            'isNot' => $isNot
        ];
    }

    /**
     * @param string $column
     * @param $value
     */
    public function and(string $column, $value) {
        $this->addCondition($column, $value,false,false);
    }

    /**
     * @param string $column
     * @param $value
     */
    public function or(string $column, $value) {
        $this->addCondition($column, $value,true,false);
    }

    /**
     * @param string $column
     * @param $value
     */
    public function andNot(string $column, $value) {
        $this->addCondition($column, $value,false,true);
    }

    /**
     * @param string $column
     * @param $value
     */
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