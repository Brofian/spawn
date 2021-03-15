<?php

namespace webu\modules\backend\models;

use webu\Database\StructureTables\WebuBackendUser;
use webu\system\Core\Base\Database\Query\QueryBuilder;
use webu\system\core\Request;
use webu\system\core\Response;

class BackendUserModel {

    /** @var string  */
    const BACKEND_USER_SESSION_KEY = "b_u_s_k";
    const BACKEND_USERNAME_SESSION_KEY = "busk_username";

    /** @var Request */
    private $request = null;
    /** @var Response */
    private $response = null;
    /** @var bool  */
    protected $isLoggedIn = false;


    /** @var int  */
    protected $id = -1;
    /** @var string  */
    protected $email = "";
    /** @var string  */
    protected $userName = "";
    /** @var string  */
    protected $sessionkey = "";



    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
        $e = $this->checkAlreadyLoggedIn();
    }



    protected function checkAlreadyLoggedIn() {
        //to log in via session these three parameters are required
        $sessionUsername = $this->request->getSessionHelper()->get(self::BACKEND_USERNAME_SESSION_KEY, false);
        $sessionKey = $this->request->getSessionHelper()->get(self::BACKEND_USER_SESSION_KEY, false);
        $cookieKey = $this->request->getCookieHelper()->get(self::BACKEND_USER_SESSION_KEY, false);


        if($sessionKey && $cookieKey && $sessionUsername && ($sessionKey == $cookieKey) ) {
            //can be logged in, if the session key and username is valid
            return $this->verifyBackendUserSessionKey($sessionKey, $sessionUsername);
        }
        else {
            $this->request->getCookieHelper()->set(self::BACKEND_USER_SESSION_KEY, "", true, "/", -1);
            $this->request->getSessionHelper()->set(self::BACKEND_USER_SESSION_KEY, null, true);
            $this->request->getSessionHelper()->set(self::BACKEND_USERNAME_SESSION_KEY, null, true);
            $this->isLoggedIn = false;
            return false;
        }
    }


    protected function verifyBackendUserSessionKey($sessionKey, $username) {
        $qb = new QueryBuilder($this->request->getDatabaseHelper()->getConnection());

        $backendUser = $qb->select("*")
            ->from(WebuBackendUser::TABLENAME)
            ->where(WebuBackendUser::COL_SESSION_HASH, $sessionKey)
            ->where(WebuBackendUser::COL_USERNAME, $username)
            ->execute();


        if(count($backendUser) == 1) {
            $backendUser = $backendUser[0];
            $this->userName = $backendUser[WebuBackendUser::RAW_COL_USERNAME];
            $this->email = $backendUser[WebuBackendUser::RAW_COL_EMAIL];
            $this->id = $backendUser[WebuBackendUser::RAW_COL_ID];
            $this->sessionkey = $backendUser[WebuBackendUser::RAW_COL_SESSION_HASH];
            $this->isLoggedIn = true;
            return true;
        }
        else {
            return false;
        }

    }


    public function tryLogin(string $username, string $password) {

        $qb = new QueryBuilder($this->request->getDatabaseHelper()->getConnection());
        $user = $qb->select("*")
            ->from(WebuBackendUser::TABLENAME)
            ->where(WebuBackendUser::COL_USERNAME, $username)
            ->where(WebuBackendUser::COL_EMAIL, $username, true)
            ->execute();

        if(count($user) != 1) {
            return false;
        }

        $user = $user[0];
        if(password_verify($password, $user[WebuBackendUser::RAW_COL_PASSWORD])) {

            $this->id = $user[WebuBackendUser::RAW_COL_ID];
            $this->userName = $user[WebuBackendUser::RAW_COL_USERNAME];
            $this->email = $user[WebuBackendUser::RAW_COL_EMAIL];
            $this->isLoggedIn = true;


            $sessionKey = md5(time());
            $insertErg = $qb->update(WebuBackendUser::TABLENAME)
                ->set(WebuBackendUser::RAW_COL_SESSION_HASH, $sessionKey)
                ->where(WebuBackendUser::RAW_COL_ID, (int)$this->id)
                ->execute();

            $this->sessionkey = $sessionKey;

            $sessionHelper = $this->request->getSessionHelper();
            $sessionHelper->set(self::BACKEND_USERNAME_SESSION_KEY, $this->userName);
            $sessionHelper->set(self::BACKEND_USER_SESSION_KEY, $sessionKey);
            $this->request->getCookieHelper()->set(self::BACKEND_USER_SESSION_KEY, $sessionKey, true, "/");



            return true;
        }

        return false;
    }



    public function logout() {
        if($this->isLoggedIn) {
            $this->request->getCookieHelper()->set(self::BACKEND_USER_SESSION_KEY, "", true, "/", -1);
            $this->request->getSessionHelper()->set(self::BACKEND_USER_SESSION_KEY, null, true);
            $this->request->getSessionHelper()->set(self::BACKEND_USERNAME_SESSION_KEY, null, true);
            $this->isLoggedIn = false;
        }

    }




    /**
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->isLoggedIn;
    }

}