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
     * @param int $id
     * @param array $values
     * @return array
     */
    public function updateById(int $id, array $values = []) {
        $stmt = $this->queryBuilder->update(WebuVariables::TABLENAME);
        $stmt->where(WebuVariables::COL_ID, $id);

        if(isset($values["name"])) {
            $stmt->set(WebuVariables::COL_NAME, $values["name"]);
        }
        if(isset($values["namespace"])) {
            $stmt->set(WebuVariables::COL_NAMESPACE, $values["namespace"]);
        }
        if(isset($values["type"])) {
            $stmt->set(WebuVariables::COL_TYPE, $values["type"]);
        }
        if(isset($values["value"])) {
            $stmt->set(WebuVariables::COL_VALUE, $values["value"]);
        }

        return $stmt->execute();
    }


    /**
     * @param int $id
     * @param array $values
     * @return array
     */
    public function create(array $values = []) : bool {
        $stmt = $this->queryBuilder->insert();
        $stmt->into(WebuVariables::TABLENAME);

        if(isset(
                $values["name"],
                $values["namespace"],
                $values["type"],
                $values["value"]
            ) == false) {

            return false;
        }

        $stmt->setValue(WebuVariables::COL_NAME, $values["name"]);
        $stmt->setValue(WebuVariables::COL_NAMESPACE, $values["namespace"]);
        $stmt->setValue(WebuVariables::COL_TYPE, $values["type"]);
        $stmt->setValue(WebuVariables::COL_VALUE, $values["value"]);

        $stmt->execute();

        return true;
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
     * @param array $idList
     * @return array|\PDOStatement
     */
    public function removeEntriesByIds(array $idList) {
        $stmt = $this->queryBuilder->delete();

        $stmt->from(WebuVariables::TABLENAME);

        foreach($idList as $id) {
            $stmt->where(WebuVariables::COL_ID, $id, true);
        }

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