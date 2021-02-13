<?php

namespace modules\Main\Controllers\Backend;


use webu\system\Core\Base\Controller\Controller;
use webu\system\Core\Database\Models\Pages;
use webu\system\core\Request;
use webu\system\core\Response;

class PagesEdit {

    /** @var Controller */
    protected $parentController;

    /** @var Request */
    protected $request;

    /** @var Response */
    protected $response;

    public function __construct(Controller $parentController)
    {
        $this->parentController = $parentController;
    }


    public function init(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;

        $this->loadPage();
    }


    protected function loadPage() {
        $id = $this->request->getCompiledURIParams()["id"];

        $pagesModel = new Pages($this->request->getDatabaseHelper()->getConnection());
        $page = $pagesModel->findById($id);

        $this->response->getTwigHelper()->assign('webu_page', $page);
    }

}