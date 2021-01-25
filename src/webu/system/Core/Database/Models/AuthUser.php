<?php

namespace webu\system\Core\Database\Models;

use webu\cache\database\table\WebuAuth;
use webu\system\Core\Base\Database\DatabaseModel;
use webu\system\Core\Custom\Debugger;


class AuthUser extends DatabaseModel {


    public function getUserAuth(int $id) {
        return $this->findById($id);
    }


    /**
     * @param int $id
     * @return array
     */
    public function findById(int $id) {
        $stmt = $this->queryBuilder->select("*");

        $stmt->from(WebuAuth::TABLENAME)
            ->where(WebuAuth::COL_ID, $id)
            ->limit(1);

        return $stmt->execute();
    }


    /**
     * @param string $username
     * @return bool|array
     */
    public function findByUsername(string $username) {
        $stmt = $this->queryBuilder->select("*");

        $stmt->from(WebuAuth::TABLENAME)
            ->where(WebuAuth::COL_USERNAME, $username)
            ->limit(1);

        $result = $stmt->execute();

        if(sizeof($result) <= 0) {
            return false;
        }


        return $result[0];
    }


}