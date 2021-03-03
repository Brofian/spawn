<?php

use \webu\system\Core\Contents\Modules\ModuleLoader;
use \webu\system\Core\Contents\Modules\Module;
use \bin\webu\IO;
use \webu\system\Core\Base\Database\DatabaseTable;
use \webu\system\Core\Base\Helper\DatabaseHelper;
use \webu\system\Core\Helper\FrameworkHelper\DatabaseStructureHelper;



$moduleLoader = new ModuleLoader();
$moduleCollection = $moduleLoader->loadModules(ROOT . "/modules");



$databaseClasses=[];
/** @var Module $module */
foreach($moduleCollection->getModuleList() as $module) {
    /** @var string $class */
    foreach($module->getDatabaseTableClasses() as $class) {
        $databaseClasses[] = $class;
    }
}
IO::printLine("> Found " . sizeof($databaseClasses) . " Database Table Files...");






IO::printLine("> Generating Tables...");

$dbhelper = new DatabaseHelper();
$createdTablesCount = 0;
$tableClasses = [];
foreach($databaseClasses as $class) {

    /** @var DatabaseTable $c */
    $c = new $class();
    $tableClasses[] = $c;

    if ($dbhelper->doesTableExist($c->getTableName())) {
        IO::printLine(IO::TAB . "- " . $c->getTableName() . " already exists");

    }
    else {
        $sql = $c->getTableCreationSQL(DB_DATABASE);
        $result = $dbhelper->query($sql);

        IO::printLine(IO::TAB . "- " . $c->getTableName() . " successfully created");
        $createdTablesCount++;
    }


    //create structure file
    DatabaseStructureHelper::createDatabaseStructure($c);
    IO::printLine(IO::TAB . "-> Created Structure File", IO::YELLOW_TEXT);
}



IO::printLine("> Created " . $createdTablesCount . " Tables", IO::GREEN_TEXT);







