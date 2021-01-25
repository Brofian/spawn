<?php


namespace webu\system\Core\Helper;

use webu\cache\database\table\WebuAuth;
use webu\system\Core\Base\Helper\DatabaseHelper;
use webu\system\Core\Custom\Debugger;
use webu\system\Core\Database\Models\AuthUser;

class UserHelper
{

    /** @var SessionHelper */
    private $sessionHelper;
    /** @var DatabaseHelper */
    private $databaseHelper;


    /** @var bool */
    private $loginStatus = false;
    const LOGINSTATUS = 'loginstatus';

    const SESSION_INDEX = 'webu_user';

    //----- user Data -----
    /** @var int */
    private $userId = null;
    const ID = "id";
    /** @var string */
    private $registrationDate = null;
    const REGISTRATIONDATE = "registrationDate";
    /** @var string */
    private $lastUpdated = null;
    const LASTUPDATED = 'lastUpdated';
    /** @var string */
    private $username = null;
    const USERNAME = 'username';
    /** @var string */
    private $email = null;
    const EMAIL = 'email';

    public function __construct(SessionHelper $sessionHelper, DatabaseHelper $databaseHelper)
    {
        $this->sessionHelper = $sessionHelper;
        $this->databaseHelper = $databaseHelper;
        $this->init();
    }


    public function init()
    {

        //Load existing data from the session
        $sessionUserInfo = $this->sessionHelper->get(self::SESSION_INDEX, false);
        if ($sessionUserInfo) {
            $sessionUserInfo = json_decode($sessionUserInfo, true);

            if (isset($sessionUserInfo[self::LOGINSTATUS])) {
                $this->loginStatus = $sessionUserInfo[self::LOGINSTATUS];
                $this->userId = $sessionUserInfo[self::ID];
                $this->username = $sessionUserInfo[self::USERNAME];
                $this->email = $sessionUserInfo[self::EMAIL];
                $this->registrationDate = $sessionUserInfo[self::REGISTRATIONDATE];
                $this->lastUpdated = $sessionUserInfo[self::LASTUPDATED];
            }
        }

    }

    /**
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function login(string $username, string $password)
    {
        $authUser = new AuthUser($this->databaseHelper->getConnection());

        $dbEntry = $authUser->findByUsername($username);
        if ($dbEntry) {
            if (password_verify($password, $dbEntry[WebuAuth::RAW_COL_PASSWORD])) {
                $this->onLoginSuccess($dbEntry);
                return true;
            }
        }
        return false;
    }

    private function onLoginSuccess($dbEntry)
    {
        $this->loginStatus = true;
        $this->userId = $dbEntry[WebuAuth::RAW_COL_ID];
        $this->username = $dbEntry[WebuAuth::RAW_COL_USERNAME];
        $this->email = $dbEntry[WebuAuth::RAW_COL_EMAIL];
        $this->registrationDate = $dbEntry[WebuAuth::RAW_COL_CREATED_AT];
        $this->lastUpdated = $dbEntry[WebuAuth::RAW_COL_UPDATED_AT];

        $userInfos = [
            self::LOGINSTATUS => $this->loginStatus,
            self::ID => $this->userId,
            self::USERNAME => $this->username,
            self::EMAIL => $this->email,
            self::REGISTRATIONDATE => $this->registrationDate,
            self::LASTUPDATED => $this->lastUpdated,
        ];


        $this->sessionHelper->set(self::SESSION_INDEX, json_encode($userInfos));
    }


    public function logout(): bool
    {
        if ($this->loginStatus) {
            $this->sessionHelper->destroySession();
            $this->loginStatus = false;
            $this->userId = null;
            $this->username = null;
            $this->email = null;
            $this->registrationDate = null;
            $this->lastUpdated = null;
            return true;
        } else {
            return false;
        }
    }



    /*
     * Getter and Setter Methods
     */


    /**
     * Logged in = true, Not Logged in = false
     */
    public function isLoggedIn(): bool
    {
        return $this->loginStatus;
    }


}