<?php

namespace webu\system\Core\Database\Models;

use webu\cache\database\table\WebuAuth;
use webu\system\Core\Base\Database\DatabaseModel;


class AuthUser extends DatabaseModel {


    /**
     * @param string $username
     * @param string $password
     * @return bool|mixed
     */
    public function tryLogin(string $username, string $password) {
        $dbEntry = $this->findByUsername($username);

        if($dbEntry) {
            $isValid = password_verify($password, $dbEntry[WebuAuth::RAW_COL_PASSWORD]);

            if($isValid) {
                return $dbEntry;
            }

        }

        return false;
    }



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
     * @return array
     */
    public function findByUsername(string $username) {
        $stmt = $this->queryBuilder->select("*");

        $stmt->from(WebuAuth::TABLENAME)
            ->where(WebuAuth::COL_USERNAME, $username)
            ->limit(1);

        return $stmt->execute();;
    }


}