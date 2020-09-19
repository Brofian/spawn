<?php

namespace webu\system\Core\Base\Database\Query\Types;

use PDOStatement;
use webu\system\Core\Base\Database\DatabaseConnection;

abstract class QueryBase {

    const JOIN = 0;
    const LEFT_JOIN = 1;
    const RIGHT_JOIN = 2;
    const INNER_JOIN = 3;
    const OUTER_JOIN = 4;

    private $boundValues = array();



    abstract function getSql() : string;

    public function execute(DatabaseConnection $connection, bool $preventFetch = false) {

        /** @var PDOStatement $stmt */
        $stmt = $connection->getConnection()->prepare($this->getSql());
        $stmt->execute($this->boundValues);

        if($preventFetch) {
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
     */
    public function bindParam(string $key, string $value) {
        $this->boundValues[$key] = $value;
        return $this;
    }


}