<?php

namespace webu\modules\Backend\Controller;

use webu\modules\backend\models\BackendUserModel;
use webu\modules\Backend\Models\SidebarElement;
use webu\system\Core\Base\Controller\BaseController;
use webu\system\core\Request;
use webu\system\core\Response;


class Index extends BaseController {

    /** @var string  */
    const LOGIN_ACTION_ID = "module.backend.login.index";

    /** @var BackendUserModel */
    protected $backendUser;




    public function onControllerStart(Request $request, Response $response)
    {
        $this->backendUser = new BackendUserModel($request, $response);
        $currentActionId = $request->getContext()->get("ActionId");

        if(!$this->backendUser->isLoggedIn() && $currentActionId != self::LOGIN_ACTION_ID) {
            $headerHelper = $response->getHeaderHelper();
            $headerHelper->redirect(self::LOGIN_ACTION_ID, [], $headerHelper::RC_REDIRECT_TEMPORARILY);
            $this->twig->setOutput("Access denied! Please log before accessing the Backend!");
            $this->stopExecution();
        }

        $this->twig->assign("sidebar", $this->createSidebar());
        return true;
    }


    /*
     *
     * Callable Actions
     *
     */

    public function index(Request $request, Response $response) {

    }


    public function login(Request $request, Response $response) {

    }

    /*
     *
     * Other Functions for this controller
     *
     */

    /**
     * @return array
     */
    private function createSidebar() {
        $sidebar = [];

        //Home
        $homeElement = new SidebarElement("Startseite", "/backend", "home", "index");
        $sidebar[] = $homeElement;

        //Variables
        $variablesElement = new SidebarElement("Variablen", "/backend/variables", "variable", "variables", "#00ff00");
        $variablesElement->addChild( new SidebarElement("Erstellen", "/backend/variables/new"));
        $sidebar[] = $variablesElement;

        //Creator
        $variablesElement = new SidebarElement("Creator", "/backend/pages", "page", "pages", "#006ba2");
        $variablesElement->addChild( new SidebarElement("Seiten", "/backend/pages") );
        $variablesElement->addChild( new SidebarElement("Elemente", "/backend/elements") );
        $sidebar[] = $variablesElement;

        return $sidebar;
    }
}