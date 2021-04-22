<?php

namespace webu\system\Core\Base\Database\Query\Types;


use MongoDB\Driver\Query;
use webu\system\Core\Base\Database\DatabaseConnection;

class QueryInsert extends QueryBase
{

    /** @var string */
    const COMMAND = 'INSERT ';

    /** @var string  */
    private $table = '';
    /** @var array  */
    private $values = array();


    /**
     * QueryInsert constructor.
     * @param DatabaseConnection $connection
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

        $sql .= 'INTO ' . $this->table . ' ';

        $sql .= '(';
            $keys = array_keys($this->values);
            $sql .= implode(',', $keys);
        $sql .= ') ';


        $sql .= 'VALUES (';
            $sql .= implode(',', $this->values);
        $sql .= ')';


        return $sql;
    }


    /**
     * @param string $tableName
     * @return QueryInsert
     */
    public function into(string $tableName) : QueryInsert
    {
        $this->table = $tableName;
        return $this;
    }

    /**
     * @param string $column
     * @param $value
     * @param string $placeholder
     * @return QueryInsert
     */
    public function setValue(string $column, $value, string $placeholder = "?") : QueryInsert {
        if($placeholder == "?" || $placeholder == "") $placeholder = ":val" . $this->boundValuesLength;


        $this->values[$column] = $placeholder;
        $this->bindValue($placeholder, $value);


        return $this;
    }

}