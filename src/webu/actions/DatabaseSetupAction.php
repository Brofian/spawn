<?php

namespace webu\actions;

use webu\system\Core\Base\Controller\ApiController;
use webu\system\Core\Base\Custom\FileCrawler;
use webu\system\Core\Base\Custom\FileEditor;
use webu\system\Core\Base\Database\DatabaseTable;
use webu\system\Core\Base\Helper\DatabaseHelper;
use webu\system\core\Request;
use webu\system\core\Response;

class DatabaseSetupAction extends ApiController {

    /** @var string  */
    const structureFileNamespace = "webu\\cache\\database\\table";
    /** @var string  */
    private $cacheDirectory = ROOT . '\\src\\webu\\system\\Core\\Database\\generated\\';


    public static function getControllerAlias(): string { return "database_setup_action"; }
    public static function getControllerRoutes(): array { return ["index"=>"run"]; }
    public function onControllerStart(Request $request, Response $response) {}
    public function onControllerStop(Request $request, Response $response) {}


    public function run(Request $request, Response $response)
    {
        $systemDBDir = ROOT . "\\src\\webu\\system\\Core\\Database";

        $dbhelper = new DatabaseHelper();
        $filecrawler = new FileCrawler();


        // Search all databaseTable classes in the core/Database Directory
        $ergs = $filecrawler->searchInfos(
            $systemDBDir,
            function($fileContent, &$ergs, $filename, $path, $relativePath) {

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
            $result = $dbhelper->query($sql);


            $counter++;

        }

        die();

        //create Database-Structure-Classes
        $this->createDatabaseStructures($tableClasses);


        /**
         * Start of test
         */
        /*
        $conn = $request->getDatabaseHelper()->getConnection();
        $query = new QueryBuilder($conn);

        //insert
        $query  ->insert()
                ->into(DebugTestTable::TABLENAME)
                ->setValue(DebugTestTable::COL_VALUE, 123)
                ->execute();

        //update
        $query  ->update(DebugTestTable::TABLENAME)
                ->set(DebugTestTable::COL_VALUE, 10)
                ->where(DebugTestTable::COL_ID, 7)
                ->execute();

        //select
        $erg = $query->select("*")
                ->from(DebugTestTable::TABLENAME)
                ->execute();

        //delete
        $query  ->delete()
                ->from(DebugTestTable::TABLENAME)
                ->where(DebugTestTable::COL_ID, 17, false, false, '>=')
                ->execute();

        Debugger::dump($erg);
        */
        /**
         * End of Test
         */


        /** @var $response Response */
        $response->getTwigHelper()->setOutput("Created ".$counter." system-tables!"); ;
    }


    /**
     * Creates classes for the given tables
     * @param array $tables
     */
    private function createDatabaseStructures(array $tables) {

        $folderName = $this->cacheDirectory;

        /** @var DatabaseTable $table */
        foreach($tables as $table) {
            $tablename = $table->getTableName();


            $filecontent =
"<?php

namespace ".self::structureFileNamespace.";

class ".toClassnameFormat($tablename)." {
    
\tconst _TABLENAME_RAW = '".$tablename."';
\tconst TABLENAME = '`".$tablename."`';
            
";

            foreach($table->getColumnNames() as $columnName) {
                $filecontent .=  "\tconst RAW_COL_" . strtoupper($columnName) . " = '" . $columnName . "';"                    . PHP_EOL;
                $filecontent .=  "\tconst COL_"     . strtoupper($columnName) . " = '`".$tablename."`.`" . $columnName . "`';" . PHP_EOL;
            }

            $filecontent .= '}';

            $fileName = $folderName . toClassnameFormat($tablename) . '.php';
            FileEditor::createFolder(dirname($fileName));
            FileEditor::insert($fileName, $filecontent);
        }

    }





}