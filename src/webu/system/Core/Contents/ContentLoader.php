<?php

namespace webu\system\Core\Contents;


/*
 * This class is called before the controller and loads some contents
 */

use webu\cache\database\table\WebuAuth;
use webu\system\Core\Base\Database\DatabaseConnection;
use webu\system\Core\Database\Models\AuthUser;
use webu\system\core\Request;

class ContentLoader {

    /** @var DatabaseConnection $connection  */
    private $connection = null;
    /** @var Request  */
    private $request = null;


    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->connection = $request->getDatabase()->getConnection();
    }

    public function init(Context $context) {
        $this->loadRequestInfos($context);
        $this->loadUser($context);
    }



    private function loadRequestInfos(Context $context) {
        $context->multiSet([
            'Controller' => $this->request->getRequestController(),
            'Action' => $this->request->getRequestActionPath(),
            'Parameters' => [
                'URI' => $this->request->getRequestURI(),
                'URIParams' => $this->request->getRequestURIParams(),
                'POST' => $this->request->getParamPost(),
                'GET' => $this->request->getParamGet(),
                'COOKIES' => $this->request->getParamCookies(),
                'SESSION' => $this->request->getParamSession(),
            ]
        ]);
    }

    private function loadUser(Context $context) {
        $webuUser = new AuthUser($this->connection);

        $sessionUserInfo = $this->request->getParamSession()->get("webu_user", false);

        if($sessionUserInfo && isset($sessionUserInfo[WebuAuth::RAW_COL_ID])) {
            $context->set('webu_user_logged_in', true);
            $userInfo = $webuUser->findById($sessionUserInfo[WebuAuth::RAW_COL_ID]);
            $context->set('webu_user', $userInfo);
        }
        else {
            $context->set('webu_user_logged_in', false);
        }

    }

}