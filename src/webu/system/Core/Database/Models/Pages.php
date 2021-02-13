<?php

namespace webu\system\Core\Database\Models;


use webu\cache\database\table\WebuPages;
use webu\system\Core\Base\Database\DatabaseModel;



class Pages extends DatabaseModel {

    /**
     * @param int $id
     * @return array
     */
    public function findById(int $id) {
        $stmt = $this->queryBuilder->select("*");

        $stmt->from(WebuPages::TABLENAME)
            ->where(WebuPages::COL_ID, $id)
            ->limit(1);

        return $stmt->execute();
    }


    /**
     * @param int $id
     * @param array $values
     * @return array
     */
    public function updateById(int $id, array $values = []) {
        $stmt = $this->queryBuilder->update(WebuPages::TABLENAME);
        $stmt->where(WebuPages::COL_ID, $id);

        $values = [
            "name" => WebuPages::COL_NAME,
            "active" => WebuPages::COL_ACTIVE
        ];

        foreach($values as $key => $value) {
            if(isset($values[$key])) {
                $stmt->set($value, $values[$key]);
            }
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
        $stmt->into(WebuPages::TABLENAME);

        if(isset(
                $values["name"],
                $values["active"]
            ) == false) {
            return false;
        }

        $stmt->setValue(WebuPages::COL_NAME, $values["name"]);
        $stmt->setValue(WebuPages::COL_ACTIVE, $values["active"]);

        $stmt->execute();

        return true;
    }



    /**
     * @param int $from
     * @param int $amount
     * @return array|\PDOStatement
     */
    public function find(int $from, int $amount, string $orderby = "id") {
        $stmt = $this->queryBuilder->select("*");

        $stmt->from(WebuPages::TABLENAME)
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

        $stmt->from(WebuPages::TABLENAME);

        foreach($idList as $id) {
            $stmt->where(WebuPages::COL_ID, $id, true);
        }

        return $stmt->execute();
    }


    /**
     * Returns the total number of saved pages
     * @return int
     */
    public function getTotalNumberOfEntries() : int {
        $stmt = $this->queryBuilder->select("COUNT(*) as count");
        $stmt->from(WebuPages::TABLENAME);

        return (int)$stmt->execute()[0]["count"];
    }

}