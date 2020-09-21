<?php

namespace webu\system\Core\Base\Database\Query\Types;

use PDOStatement;
use webu\system\Core\Base\Database\DatabaseConnection;

abstract class QueryBase {

    /** @var int  */
    const JOIN = 0;
    /** @var int  */
    const LEFT_JOIN = 1;
    /** @var int  */
    const RIGHT_JOIN = 2;
    /** @var int  */
    const INNER_JOIN = 3;
    /** @var int  */
    const OUTER_JOIN = 4;

    /** @var array  */
    private $boundValues = array();


    /**
     * Abstract
     * @return string
     */
    abstract function getSql() : string;

    /**
     * Executes the query and returns the result
     *
     * @param DatabaseConnection $connection
     * @param bool $preventFetch
     * @return array|PDOStatement
     */
    public function execute(DatabaseConnection $connection, bool $preventFetching = false) {

        /** @var PDOStatement $stmt */
        $stmt = $connection->getConnection()->prepare($this->getSql());
        $stmt->execute($this->boundValues);

        if($preventFetching) {
            $return = $stmt;
        }
        else {
            $return = $stmt->fetchAll();
        }

        return $return;
    }




    /**
     * Binds param to placeholders in the query
     * Use "addParam()" for "?" placehoders
     * @param $param
     * @return $this
     */
    public function bindValue($param) {
        $boundValues[] = $param;
        return $this;
    }

    /**
     * Binds param to placeholders in the query
     * Use "bindParam()" for ":key" placehoders
     *
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function bindParam(string $key, string $value) {
        $this->boundValues[$key] = $value;
        return $this;
    }



    public function formatParam(&$value) {
        if(is_string($value)) {
            //add quotationmarks to string
            $value = '\'' . $value . '\'';
            return true;
        }
        if(is_object($value)) {
            //convert objects to json and add quotationmarks
            $value = '\'' . json_encode($value) . '\'';
            return true;
        }

        return false;
    }

}