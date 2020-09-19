<?php

namespace webu\system\Core\Base\Database\Query\Types;


class QuerySelect extends QueryBase
{

    const COMMAND = 'SELECT ';

    private $columns = '*';
    private $table = '';
    private $where = array();
    private $orderby = '';
    private $limit = '';
    private $join = array();

    public function __construct($columns = null)
    {
        if ($columns != null) {
            $this->setColumns($columns);
        }
        return $this;
    }

    public function getSql(): string
    {
        $sql = self::COMMAND . ' ';
        $sql .= $this->columns . ' ';
        $sql .= 'FROM ' . $this->table . ' ';

        if(sizeof($this->join) > 0) {
            foreach($this->join as $join) {
                $sql .= $join . ' ';
            }
        }

        if (sizeof($this->where) > 0) {

            $counter = 0;

            foreach ($this->where as $where) {

                if ($counter == 0) {
                    $sql .= 'WHERE ';
                } else {
                    $sql .= 'AND ';
                }

                $sql .= $where . ' ';

                $counter++;
            }

        }

        if ($this->orderby != '') {
            $sql .= 'ORDER BY ' . $this->orderby . ' ';
        }

        if ($this->limit != '') {
            $sql .= 'LIMIT ' . $this->limit . ' ';
        }

        return $sql;
    }


    public function from(string $tableName)
    {
        $this->table = $tableName;
        return $this;
    }

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
    }

    public function join(string $tableName, string $column1, string $column2, int $joinType = 0, string $as = '') {

        $j = '';
        switch($joinType) {
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

        if($as != '') {
            $j .= 'AS ' . $as . ' ';
        }

        $j .= 'ON ' . $column1 . '=' . $column2;

        $this->join[] = $j;

        return $this;
    }


    public function where(string $column, $value)
    {
        if(is_string($value)) {
            $value = '\'' . $value . '\'';
        }

        $this->where[] = $column . '=' . $value;

        return $this;
    }

    public function orderby(string $column, bool $reverse = false)
    {
        $direction = ($reverse) ? 'DESC' : 'ASC';
        $this->orderby = $column . ' ' . $direction;

        return $this;
    }

    public function limit(int $val1, int $val2 = null)
    {
        $this->limit = $val1;
        if ($val2 != null) {
            $this->limit = $val2;
        }


        return $this;
    }

}