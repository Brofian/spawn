<?php

namespace webu\system\Core\Base\Database\Query\Types;


use webu\system\Core\Base\Database\DatabaseConnection;

class QuerySelect extends QueryBase
{

    /** @var string */
    const COMMAND = 'SELECT ';

    /** @var string */
    private $columns = '*';
    /** @var string */
    private $table = '';
    /** @var array */
    private $where = array();
    /** @var array */
    private $orderby = array();
    /** @var array */
    private $groupBy = array();
    /** @var string */
    private $limit = '';
    /** @var array */
    private $join = array();

    /**
     * QuerySelect constructor.
     * @param mixed $columns
     * @return QuerySelect
     */
    public function __construct(DatabaseConnection $connection, $columns = null)
    {
        parent::__construct($connection);

        if ($columns != null) {
            $this->setColumns($columns);
        }
        return $this;
    }

    /**
     * Builds an returns the sql
     *
     * @return string
     */
    public function getSql(): string
    {
        $sql = self::COMMAND . ' ';
        $sql .= $this->columns . ' ';
        $sql .= 'FROM ' . $this->table . ' ';

        if (sizeof($this->join) > 0) {
            foreach ($this->join as $join) {
                $sql .= $join . ' ';
            }
        }

        foreach ($this->where as $where) {
            $sql .= $where . ' ';
        }

        if (sizeof($this->orderby) > 0)
        {
            $sql .= 'ORDER BY ' .  implode(',', $this->orderby) . ' ';
        }

        if (sizeof($this->groupBy) > 0)
        {
            $sql .= 'GROUP BY ' .  implode(',', $this->groupBy) . ' ';
        }

        if ($this->limit != '') {
            $sql .= 'LIMIT ' . $this->limit . ' ';
        }

        return $sql;
    }


    /**
     * @param string $tableName
     * @return QuerySelect
     */
    public function from(string $tableName)
    {
        $this->table = $tableName;
        return $this;
    }

    /**
     * @param $columns
     * @return QuerySelect
     */
    public function setColumns($columns)
    {
        if (is_string($columns)) {
            $this->columns = $columns;
        } else if (is_array($columns)) {
            foreach ($columns as $column) {
                if ($this->columns == '*') {
                    $this->columns = $column;
                } else {
                    $this->columns .= ', ' . $column;
                }
            }
        }
        return $this;
    }

    /**
     * Adds a join to the query. The joinTypes can be called from QuerySelect::constants
     *
     * @param string $tableName
     * @param string $column1
     * @param string $column2
     * @param int $joinType
     * @param string $as
     * @return QuerySelect
     */
    public function join(string $tableName, string $column1, string $column2, int $joinType = 0, string $as = '')
    {

        $j = '';
        switch ($joinType) {
            case 1:
                $j .= 'LEFT JOIN ';
                break;
            case 2:
                $j .= 'RIGHT JOIN ';
                break;
            case 3:
                $j .= 'INNER JOIN ';
                break;
            case 4:
                $j .= 'OUTER JOIN ';
                break;
            case 0:
            default:
                $j .= 'JOIN ';
                break;
        }

        $j .= $tableName . ' ';

        if ($as != '') {
            $j .= 'AS ' . $as . ' ';
        }

        $j .= 'ON ' . $column1 . '=' . $column2;

        $this->join[] = $j;

        return $this;
    }


    /**
     * Adds a where-condition
     *
     * @param string $column
     * @param $value
     * @return QuerySelect
     */
    public function where(string $column, $value, bool $isOr = false, bool $not = false)
    {
        $isString = $this->formatParam($value);

        $prefix = '';
        if($not) {
            if(sizeof($this->where) == 0) {
                $prefix = 'WHERE NOT ';
            }
            else if($isOr) {
                $prefix = 'OR NOT ';
            }
            else {
                $prefix = 'AND NOT ';
            }
        }
        else {
            if(sizeof($this->where) == 0) {
                $prefix = 'WHERE ';
            }
            else if($isOr) {
                $prefix = 'OR ';
            }
            else {
                $prefix = 'AND ';
            }
        }

        $operator = ($isString) ? 'LIKE' : '=';

        $this->where[] = $prefix . $column . $operator . $value;

        return $this;
    }

    /**
     * Adds the orderby-parameter
     *
     * @param string $column
     * @param bool $reverse
     * @return QuerySelect
     */
    public function orderby(string $column, bool $reverse = false)
    {
        $direction = ($reverse) ? 'DESC' : 'ASC';
        $this->orderby = $column . ' ' . $direction;

        return $this;
    }

    /**
     * Adds the limit-parameter
     *
     * @param int $val1
     * @param int|null $val2
     * @return QuerySelect
     */
    public function limit(int $val1, int $val2 = null)
    {
        $this->limit = $val1;
        if ($val2 != null) {
            $this->limit .= '' . $val2;
        }

        return $this;
    }


    public function groupBy(string $column) {
        $this->groupBy[] = $column;
    }

}