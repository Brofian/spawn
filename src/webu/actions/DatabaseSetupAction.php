<?php

namespace webu\actions;

use webu\system\Core\Base\Controller\ApiController;
use webu\system\Core\Base\Custom\FileCrawler;
use webu\system\Core\Base\Database\DatabaseTable;
use webu\system\Core\Base\Helper\DatabaseHelper;
use webu\system\Core\Custom\Debugger;
use webu\system\core\Request;
use webu\system\core\Response;

class DatabaseSetupAction extends ApiController {

    public function run(Request $request, Response $response)
    {
        $systemDBDir = ROOT . "\\src\\webu\\system\\Core\\Database";

        $dbhelper = new DatabaseHelper();
        $filecrawler = new FileCrawler();


        // Search all databaseTable classes in the core/Database Directory
        $ergs = $filecrawler->searchInfos(
            $systemDBDir,
            function($fileContent, &$ergs, $filename, $path) {

                $regex = '/class (.*) extends DatabaseTable/m';
                preg_match($regex, $fileContent, $matches);
                if(sizeof($matches) < 2) {
                    return;
                }
                $class = $matches[1];

                $regex = '/name'.'space (.*);/m';
                preg_match($regex, $fileContent, $matches);
                if(sizeof($matches) < 2) {
                    return;
                }
                $nameSpace = $matches[1];


                $ergs[] = $nameSpace . '\\' . $class;
            }
        );

        //Create all non existing tables
        $counter = 0;
        foreach($ergs as $dbclass) {

            /** @var DatabaseTable $c */
            $c = new $dbclass();
            $tableName = $c->getTableName();

            if($dbhelper->doesTableExist($tableName)) {
                continue;
            }


            $sql = $c->getTableCreationSQL(DB_DATABASE);
            $dbhelper->query($sql);

            $counter++;
        }

        /** @var $response Response */
        $response->getTwigHelper()->setOutput("Created ".$counter." system-tables!"); ;
    }


}