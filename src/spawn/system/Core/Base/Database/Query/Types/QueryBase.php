<?php

namespace spawn\system\Core\Base\Database\Query\Types;

use PDOStatement;
use spawn\system\Core\Base\Database\DatabaseConnection;
use spawn\system\Core\Base\Database\Query\QueryCondition;

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
    public $boundValues = array();
    protected $boundValuesLength = 0;

    protected $conditions = [];

    /** @var DatabaseConnection  */
    protected $connection = null;


    public function __construct(DatabaseConnection $connection)
    {
        $this->connection = $connection;
    }


    /**
     * Abstract
     * @return string
     */
    abstract function getSql() : string;

    /**
     * Executes the query and returns the result
     *
     * @param bool $preventFetching
     * @return array|PDOStatement
     */
    public function execute(bool $preventFetching = false) {

        /** @var PDOStatement $stmt */
        $stmt = $this->connection::getConnection()->prepare($this->getSql());

        foreach($this->boundValues as $key => $boundValue) {
            $stmt->bindValue($key, $boundValue);
        }


        $stmt->execute();

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
     * @param $placeholder
     * @param $param
     * @return $this
     */
    public function bindValue($placeholder, $param) {
        $this->boundValues[$placeholder] = $param;
        $this->boundValuesLength = sizeof($this->boundValues);
        return $this;
    }




    protected function formatParam(&$value) {
        if(is_string($value)) {
            //add quotation-marks to string
            $value = '\'' . $value . '\'';
            return true;
        }
        else if(is_object($value)) {
            //convert objects to json and add quotation-marks
            $value = '\'' . json_encode($value) . '\'';
            return true;
        }

        return false;
    }


    public function applyCondition(QueryCondition $condition) {

        if(count($condition->getConditions()) < 1) return;


        $where = "( ";

        $count = 0;
        foreach($condition->getConditions() as $cond) {
            if($count != 0) {
                if($cond["isOr"]) {
                    $where .= "OR ";
                }
                else {
                    $where .= "AND ";
                }
            }

            if($cond["isNot"]) {
                $where .= "NOT ";
            }

            $seperator = (is_string($cond["value"])) ? " LIKE " : " = ";
            $placeholder = ":cond" . count($this->conditions);
            $this->bindValue($placeholder, $cond["value"]);

            $where .= $cond["column"] . $seperator . $placeholder . " ";
        }

        $where .= ") ";


        $this->conditions[] = [
           'necessary' => $condition->isNecessary(),
           'condition' => $where
        ];
    }

}