<?php

namespace modules\Main\Controllers;

use modules\Main\Controllers\Backend\BackendIndex;
use modules\Main\Controllers\Backend\CreatorElements;
use modules\Main\Controllers\Backend\CreatorIndex;
use modules\Main\Controllers\Backend\CreatorPages;
use modules\Main\Controllers\Backend\PagesEdit;
use modules\Main\Controllers\Backend\PagesIndex;
use modules\Main\Controllers\Backend\VariablesEdit;
use modules\Main\Controllers\Backend\VariablesIndex;
use src\Models\SidebarElement;
use webu\system\Core\Base\Controller\Controller;
use webu\system\Core\Contents\Context;
use webu\system\Core\Database\Models\Variable;
use webu\system\Core\Helper\UserHelper;
use webu\system\core\Request;
use webu\system\core\Response;

class Backend extends Controller {

    /**
     * @inheritDoc
     */
    public static function getControllerAlias(): string
    {
        return 'backend';
    }

    /**
     * @inheritDoc
     */
    public static function getControllerRoutes(): array
    {
        return [
            '' => 'index',
            'login' => 'login',
            'logout' => 'logout',
            'variables' => 'variables',
            'pages' => 'pages'
        ];
    }

    public function onControllerStart(Request $request, Response $response) {
        $request->getContext()->setBackendContext();

        $request->getContext()->set("sidebar", $this->createSidebar());

    }

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

    public function onControllerStop(Request $request, Response $response) {}

    /**
     * checks if the user is logged in
     */
    private function loginRestriction(Request $request, Response $response) {
        //check if the login Restriction is
        /** @var UserHelper $userHelper */
        $userHelper = $request->getContext()->get(Context::INDEX_USER);
        return !!$userHelper->isLoggedIn();
    }

    public function index(Request $request, Response $response) {

        if($this->loginRestriction($request, $response) == false) {
            $this->login($request, $response);
            return;
        }


        //this page can be called from other functions, so reset the action to index
        $response->getTwigHelper()->assign('action', 'index');
    }


    public function login(Request $request, Response $response) {
        if($this->loginRestriction($request, $response) == true) {
            $this->index($request, $response);
            return;
        }

        //this page can be called from other functions, so reset the action to login
        $response->getTwigHelper()->assign('action', 'login');
    }



    public function logout(Request $request, Response $response) {
        if($this->loginRestriction($request, $response) == false) {
            $this->login($request, $response);
            return;
        }

        /** @var $userHelper $userHelper */
        $userHelper = $request->getContext()->get(Context::INDEX_USER);
        $userHelper->logout();
        $response->getTwigHelper()->setRenderFile("backend/login/logout.html.twig");
    }



    public function variables(Request $request, Response $response) {
        if($this->loginRestriction($request, $response) == false) {
            $this->login($request, $response);
            return;
        }


        $this->callSubController($request, $response, 0, [
            "edit" => new VariablesEdit($this),
            "new" => false,
            "index" => new VariablesIndex($this)
        ], "index");

    }




    public function creator(Request $request, Response $response)
    {
        if($this->loginRestriction($request, $response) == false) {
            $this->login($request, $response);
            return;
        }
    }


    public function pages(Request $request, Response $response)
    {
        if($this->loginRestriction($request, $response) == false) {
            $this->login($request, $response);
            return;
        }


        $this->callSubController($request, $response, 0, [
            "index" => new PagesIndex($this),
            "edit" => new PagesEdit($this),
            "new" => false,
        ], "index");

    }

    /*public function elements(Request $request, Response $response)
    {
        if($this->loginRestriction($request, $response) == false) {
            $this->login($request, $response);
            return;
        }

        $subController = new CreatorElements($this);
        $subController->init($request, $response);
    }
    */

    /**
     * Reads and returns the uri param at the given position or else false
     *
     * @param Request $request
     * @param int $pos
     * @param array $availablePages
     * @return bool|string
     */
    protected function getUriParamAtPos(Request $request, int $pos, array $availablePages = []) {

        if(count($request->getCompiledURIParams()) < ($pos+1)) {
            return false;
        }

        $uriParam = $request->getCompiledURIParams()[$pos];

        if(count($availablePages) > 0 && !isset($availablePages[$uriParam])) {
            return false;
        }

        return $uriParam;
    }



    protected function callSubController(Request $request, Response $response, int $urlParamPos = 0, array $availableSubPages = [], string $defaultSubPage = "") {
        $subPage = $this->getUriParamAtPos($request, $urlParamPos, $availableSubPages);
        if($subPage === false) {
            $subPage = $defaultSubPage;
        }

        $response->getTwigHelper()->assign("subpage", $subPage);

        if($availableSubPages[$subPage] == false) {
            return;
        }


        $subController = $availableSubPages[$subPage];
        $subController->init($request, $response);

    }


}