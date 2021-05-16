<?php

use webu\system\core\base\Migration;
use bin\webu\IO;
use webu\system\Core\Contents\Modules\ModuleCollection;
use webu\system\Core\Helper\URIHelper;
use webu\system\Core\Contents\Modules\Module;
use webu\system\Core\Base\Custom\FileEditor;
use webu\system\Core\Base\Helper\DatabaseHelper;


/*
 * Load all Migration files
 */

/** @var ModuleCollection $moduleCollection */
$moduleCollection = include(__DIR__ . "/../modules/callable/list-modules.php");


$migrations = [];
/** @var Module $module */
foreach($moduleCollection->getModuleList() as $module) {

    $migrationsFolder = URIHelper::joinMultiplePaths($module->getBasePath(),"src", "Database", "Migrations");

    if(!file_exists($migrationsFolder)) {
        continue;
    }

    $migrationFiles = scandir($migrationsFolder);

    foreach($migrationFiles as $file) {
        if($file == "." || $file == "..") continue;
        $path = URIHelper::joinPaths($migrationsFolder, $file);

        $fileContent = FileEditor::getFileContent($path);

        //read classname
        $matches = [];
        $isMigration = preg_match_all('/clas'.'s ([^{]*) extends Migr'.'ation/', $fileContent, $matches);
        if(!$isMigration || count($matches) < 2) continue;
        $className = $matches[1][0];

        //read namespace
        $matches = [];
        $hasNamespace = preg_match_all('/name'.'space ([^;]*);/', $fileContent, $matches);
        if(!$hasNamespace || count($matches) < 2) continue;
        $namespace = $matches[1][0];

        /** @var Migration|string $fullClassName */
        $fullClassName = $namespace . "\\" . $className;

        $migrations[] = [$fullClassName::getUnixTimestamp(),$fullClassName];
    }
}



//sort all migrations by their timestamp (0 -> lowest)
usort($migrations, function($a, $b) {
    return ($a[0] < $b[0]) ? -1 : 1;
});



/*
 * Get already executed Migrations
 */


$dbHelper = new DatabaseHelper();
$migrationTableExists = $dbHelper->doesTableExist('webu_migrations');

$executedMigrations = [];
if($migrationTableExists) {
    //load executed migrations
    $erg = $dbHelper->query("SELECT * FROM webu_migrations");

    foreach($erg as $item) {
        $executedMigrations[] = $item["class"] . "-" . $item["timestamp"];
    }
}


/*
 *
 *  Execute Migrations
 *
 */


$newMigrations = [];
$problems = 0;

/** @var string $migration */
foreach($migrations as $migration) {

    $migrationTimestamp = $migration[0];
    $migrationClass = $migration[1];


    if(in_array(str_replace("\\", "/", (string)$migrationClass) . "-" . $migrationTimestamp, $executedMigrations)) {
        continue;
    }

    try {
        /** @var Migration $m */
        $m = new $migrationClass();
        $m->run($dbHelper);

        $newMigrations[] = $migration;
        IO::printLine("> executed Migration \"$migrationClass\"", IO::GREEN_TEXT);
    }
    catch(Exception $e) {
        IO::endLine();
        IO::print("An error occured while running Migration ", IO::RED_TEXT);
        IO::print($migrationClass, IO::YELLOW_TEXT);
        IO::printLine("! Skipping!", IO::RED_TEXT);
        IO::endLine();
        $problems++;

        break;
    }

}


/*
 *
 * Save new Migrations
 *
 */
$sql = "INSERT INTO `webu_migrations` (`class`,`timestamp`) VALUES ";
$isFirst = true;
foreach($newMigrations as $newMigration) {

    switch($isFirst) {
        case true:
            $values = "";
            break;
        default:
            $values = ",";
            break;
    }

    $values .= "(\"". str_replace("\\", "/", (string)$newMigration[1]) ."\",\"".(int)$newMigration[0] ."\")";

    $sql .= $values;
    $isFirst = false;
}
$dbHelper->query($sql);


if($problems) {
    IO::printLine("Successfully executed ". count($newMigrations) ." Migrations!", IO::YELLOW_TEXT);
    IO::printLine($problems ." Exceptions occured! Please check the output above!", IO::RED_TEXT);
}
else {
    IO::printLine("Successfully executed ". count($newMigrations) ." Migrations!", IO::GREEN_TEXT);
}
