<?php

namespace webu\system\Core\Contents;


/*
 * This class is called before the controller and loads some contents
 */

use webu\cache\database\table\WebuAuth;
use webu\system\Core\Base\Database\DatabaseConnection;
use webu\system\Core\Database\Models\AuthUser;
use webu\system\Core\Helper\UserHelper;
use webu\system\core\Request;

class ContentLoader {

    /** @var DatabaseConnection $connection  */
    private $connection = null;
    /** @var Request  */
    private $request = null;


    /**
     * ContentLoader constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->connection = $request->getDatabaseHelper()->getConnection();
    }

    /**
     * @param Context $context
     */
    public function init(Context $context) {
        $this->loadRequestInfos($context);
        $this->loadUser($context);
    }


    /**
     * @param Context $context
     */
    private function loadRequestInfos(Context $context) {
        $context->multiSet([
            'Controller' => $this->request->getRequestController(),
            'Action' => $this->request->getRequestActionPath(),
            'Parameters' => [
                'URI' => $this->request->getRequestURI(),
                'URIParams' => $this->request->getRequestURIParams(),
                'POST' => $this->request->getParamPost(),
                'GET' => $this->request->getParamGet(),
                'COOKIES' => $this->request->getCookieHelper(),
                'SESSION' => $this->request->getSessionHelper(),
            ]
        ]);
    }

    /**
     * @param Context $context
     */
    private function loadUser(Context $context) {
        $context->set("user", new UserHelper(
            $this->request->getSessionHelper(),
            $this->request->getDatabaseHelper()
        ));
    }

}