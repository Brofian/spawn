<?php

namespace webu\actions;

use webu\system\Core\Base\Controller\ApiController;
use webu\system\Core\Base\Custom\FileCrawler;
use webu\system\Core\Base\Custom\FileEditor;
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

        $tableClasses = array();

        //Create all non existing tables
        $counter = 0;
        foreach($ergs as $dbclass) {

            /** @var DatabaseTable $c */
            $c = new $dbclass();
            $tableClasses[] = $c;

            $tableName = $c->getTableName();

            if($dbhelper->doesTableExist($tableName)) {
                continue;
            }


            $sql = $c->getTableCreationSQL(DB_DATABASE);
            $dbhelper->query($sql);

            $counter++;
        }


        //create Database-Structure-Classes
        $this->createDatabaseStructures($tableClasses);

        /** @var $response Response */
        $response->getTwigHelper()->setOutput("Created ".$counter." system-tables!"); ;
    }


    private function createDatabaseStructures(array $tables) {

        $folderName = ROOT . '\\var\\cache\\database\\';

        /** @var DatabaseTable $table */
        foreach($tables as $table) {
            $tablename = $table->getTableName();


            $filecontent =
"<?php

namespace webu\\cache\\database\\table;
                
class ".$tablename." {
    
\tconst _TABLENAME_RAW = '".$tablename."';
\tconst TABLENAME = '`".$tablename."`';
            
";

            foreach($table->getColumnNames() as $columnName) {
                $filecontent .=  "\tconst _" . strtoupper($columnName) . "_RAW = '" . $columnName . "';" . PHP_EOL;
                $filecontent .=  "\tconst " . strtoupper($columnName) . " = '`".$tablename."`.`" . $columnName . "`';" . PHP_EOL;
            }

            $filecontent .= '}';

            $fileName = $folderName . $tablename . '.php';
            FileEditor::createFolder(dirname($fileName));
            FileEditor::insert($fileName, $filecontent);
        }

    }


}