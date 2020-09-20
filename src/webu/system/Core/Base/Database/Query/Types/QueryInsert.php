<?php

namespace webu\system\Core\Base\Database\Query\Types;


use MongoDB\Driver\Query;

class QueryInsert extends QueryBase
{

    /** @var string */
    const COMMAND = 'INSERT ';

    //INSERT INTO table (col1,col2,col3) VALUES (val1,val2,val3)
    public $table = '';
    public $values = array();



    public function __construct()
    {
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

        $sql .= 'INTO ' . $this->table . ' ';

        $sql .= '(';
            $counter = 0;
            foreach($this->values as $column => $value) {
                if($counter != 0) {
                    $sql .= ',';
                }
                $sql .= $column;
                $counter++;
            }
        $sql .= ') ';


        $sql .= 'VALUES (';
            $counter = 0;
            foreach($this->values as $column => $value) {
                if($counter != 0) {
                    $sql .= ',';
                }
                $sql .= $value;
                $counter++;
            }
        $sql .= ')';


        return $sql;
    }


    /**
     * @param string $tableName
     * @return QueryInsert
     */
    public function into(string $tableName)
    {
        $this->table = $tableName;
        return $this;
    }


    public function setValue(string $column, $value) {

        $this->formatParam($value);

        $this->values[$column] = $value;
    }

}