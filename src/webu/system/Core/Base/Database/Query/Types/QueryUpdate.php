<?php

namespace webu\system\Core\Base\Database\Query\Types;


use MongoDB\Driver\Query;
use webu\system\Core\Base\Database\DatabaseConnection;

class QueryUpdate extends QueryBase
{

    /** @var string */
    const COMMAND = 'UPDATE ';

    private $table = '';
    private $values = array();
    private $where = array();


    public function __construct(DatabaseConnection $connection, string $table)
    {
        parent::__construct($connection);

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

        $sql .= ' ';
        foreach ($this->where as $where) {
            $sql .= $where . ' ';
        }

        $isFirst = (count($this->where) < 1);
        foreach ($this->conditions as $condition) {
            if(!$isFirst) {
                $connector = ($condition["necessary"]) ? " AND " : " OR ";
                $sql .= $connector;
            }
            else {
                $isFirst = false;
            }

            $sql .= $condition["condition"] . " ";
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
    public function set(string $column, $value, $placeholder = "?") : QueryUpdate{
        if($placeholder == "?" || $placeholder == "") $placeholder = ":val" . $this->boundValuesLength;


        $this->values[$column] = $placeholder;
        $this->bindValue($placeholder, $value);

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

        $operator = ($isString) ? ' LIKE ' : ' = ';

        $ph = ":where".count($this->where);
        $this->bindValue($ph, $value);
        $this->where[] = $prefix . $column . $operator . $ph;

        return $this;
    }


}