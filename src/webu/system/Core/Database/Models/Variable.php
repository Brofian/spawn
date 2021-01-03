<?php

namespace webu\system\Core\Database\Models;

use webu\cache\database\table\WebuAuth;
use webu\cache\database\table\WebuVariables;
use webu\system\Core\Base\Database\DatabaseModel;
use webu\system\Core\Custom\Debugger;


class Variable extends DatabaseModel {

    /**
     * @param int $id
     * @return array
     */
    public function findById(int $id) {
        $stmt = $this->queryBuilder->select("*");

        $stmt->from(WebuVariables::TABLENAME)
            ->where(WebuVariables::COL_ID, $id)
            ->limit(1);

        return $stmt->execute();
    }


    /**
     * @param int $from
     * @param int $amount
     * @return array|\PDOStatement
     */
    public function findEntries(int $from, int $amount, string $orderby = "id") {
        $stmt = $this->queryBuilder->select("*");

        $stmt->from(WebuVariables::TABLENAME)
            ->limit($from, $amount)
            ->orderby($orderby);

        return $stmt->execute();
    }


    /**
     * Returns the total number of saved variables
     * @return int
     */
    public function getTotalNumberOfEntries() : int {
        $stmt = $this->queryBuilder->select("COUNT(*) as count");
        $stmt->from(WebuVariables::TABLENAME);

        return (int)$stmt->execute()[0]["count"];
    }

}