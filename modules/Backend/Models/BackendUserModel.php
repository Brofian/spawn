<?php

namespace webu\modules\backend\models;

use webu\Database\StructureTables\WebuBackendUser;
use webu\system\Core\Base\Database\Query\QueryBuilder;
use webu\system\Core\Base\Database\Query\Types\QuerySelect;
use webu\system\Core\Base\Helper\DatabaseHelper;
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
        $this->checkAlreadyLoggedIn();
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
            return false;
        }
    }


    protected function verifyBackendUserSessionKey($sessionKey, $username) {
        $qb = new QueryBuilder($this->request->getDatabaseHelper()->getConnection());

        dd("hello world");

        $backendUser = $qb->select("*")
            ->from(WebuBackendUser::TABLENAME)
            ->where(WebuBackendUser::COL_SESSION_HASH, $sessionKey)
            ->where(WebuBackendUser::COL_USERNAME, $username)
            ->execute();


        dd($backendUser);

        if(count($backendUser) == 1) {
            $this->userName = $backendUser[0][WebuBackendUser::COL_USERNAME];
            $this->email = $backendUser[0][WebuBackendUser::COL_EMAIL];
            $this->id = $backendUser[0][WebuBackendUser::COL_ID];
            $this->sessionkey = $backendUser[0][WebuBackendUser::COL_SESSION_HASH];
            $this->isLoggedIn = true;
            return true;
        }
        else {
            return false;
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