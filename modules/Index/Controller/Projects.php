<?php

namespace webu\modules\Index\Controller;

use webu\Database\StructureTables\WebuProjects;
use webu\modules\Index\Models\BaseListing;
use webu\modules\Index\Models\BasicListing;
use webu\modules\Index\Models\Project;
use webu\system\Core\Base\Controller\BaseController;
use webu\system\Core\Base\Database\DatabaseConnection;
use webu\system\core\Request;
use webu\system\core\Response;

class Projects extends BaseController  {

    /** @var DatabaseConnection */
    private $connection;

    public function onControllerStart(Request $request, Response $response)
    {
        $this->connection = $request->getDatabaseHelper()->getConnection();
        $request->getContext()->set('main_navigation', Index::createMainNavigation());
    }



    public function list(Request $request, Response $response, $page = 1) {
        $listing = new BasicListing();

        $listing->setEntries(Project::getCount($this->connection));

        $listing->setCurrentPage((int)$page);


        $listing->addElements(
            Project::find(
                $listing->getItemsPerPage()*$listing->getCurrentPage(true),
                $listing->getItemsPerPage(),
                $this->connection
            )
        );


        $this->twig->assign('listing', $listing);
    }




}