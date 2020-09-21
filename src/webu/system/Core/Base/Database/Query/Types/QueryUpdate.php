<?php

namespace webu\system\Core\Base\Database\Query\Types;


use MongoDB\Driver\Query;

class QueryUpdate extends QueryBase
{

    /** @var string */
    const COMMAND = 'UPDATE ';

    public $table = '';
    public $values = array();
    public $where = array();


    public function __construct(string $table)
    {
        $this->setTable($table);
        return $this;
    }

    /**
     * Builds an returns the sql
     *
     * @return string
     */
    public function getSql(): string
    {
        $sql = self::COMMAND;

        $sql .= $this->table . ' ';

        $sql .= 'SET ';

            $counter = 0;
            foreach($this->values as $column => $key) {
                if($counter != 0) {
                    $sql .= ',';
                }
                $sql .= $column . '=' . $key;
                $counter++;
            }

        foreach ($this->where as $where) {
            $sql .= $where . ' ';
        }

        return $sql;
    }


    /**
     * @param string $tableName
     * @return QueryUpdate
     */
    public function setTable(string $tableName) : QueryUpdate
    {
        $this->table = $tableName;
        return $this;
    }


    /**
     * @param string $column
     * @param $value
     * @return QueryUpdate
     */
    public function set(string $column, $value) : QueryUpdate{
        $this->formatParam($value);
        $this->values[$column] = $value;
        return $this;
    }


    /**
     * Adds a where-condition
     *
     * @param string $column
     * @param $value
     * @return QueryUpdate
     */
    public function where(string $column, $value, bool $isOr = false, bool $not = false) : QueryUpdate
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


}