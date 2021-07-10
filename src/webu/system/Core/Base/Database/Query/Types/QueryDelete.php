<?php

namespace webu\system\Core\Base\Database\Query\Types;


use webu\system\Core\Base\Database\DatabaseConnection;

class QueryDelete extends QueryBase
{

    /** @var string */
    const COMMAND = 'DELETE ';

    //DELETE FROM table WHERE a = 3
    /** @var string  */
    private $table = '';
    /** @var array  */
    private $where = array();


    /**
     * QueryDelete constructor.
     * @param DatabaseConnection $connection
     * @return QueryDelete
     */
    public function __construct(DatabaseConnection $connection)
    {
        parent::__construct($connection);
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
        $sql .= 'FROM ' . $this->table . ' ';

        foreach ($this->where as $where) {
            $sql .= $where . ' ';
        }

        $isFirst = (count($this->where) < 1);
        foreach ($this->conditions as $condition) {
            if($isFirst) {
                $sql .= " WHERE 1 ";
                $isFirst = false;
            }

            $connector = ($condition["necessary"]) ? " AND " : " OR ";
            $sql .= $connector;
            $sql .= $condition["condition"] . " ";
        }

        return $sql;
    }


    /**
     * @param string $tableName
     * @return QueryDelete
     */
    public function from(string $tableName) : QueryDelete
    {
        $this->table = $tableName;
        return $this;
    }


    /**
     * Adds a where-condition
     *
     * @param string $column
     * @param $value
     * @return QueryDelete
     */
    public function where(string $column, $value, bool $isOr = false, bool $not = false, string $operator = null) : QueryDelete
    {
        $isString = is_string($value);

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


        //if operator is set: operator
        //if operator is not set: either LIKE or =
        $op = $operator;
        if(!$op) {
            $op = ($isString) ? ' LIKE ' : ' = ';
        }

        $this->where[] = $prefix . $column . $op . $value;

        return $this;
    }


}