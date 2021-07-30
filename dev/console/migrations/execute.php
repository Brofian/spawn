<?php

use bin\spawn\IO;
use spawn\system\Core\Base\Custom\FileEditor;
use spawn\system\Core\Base\Helper\DatabaseHelper;
use spawn\system\Core\base\Migration;
use spawn\system\Core\Contents\Modules\Module;
use spawn\system\Core\Contents\Modules\ModuleCollection;
use spawn\system\Core\Helper\URIHelper;


/*
 * Load all Migration files
 */

/** @var ModuleCollection $moduleCollection */
$moduleCollection = include(__DIR__ . "/../modules/callable/list-modules.php");


$migrations = [];
/** @var Module $module */
foreach($moduleCollection->getModuleList() as $module) {

    $migrationsFolder = URIHelper::joinMultiplePaths(ROOT,$module->getBasePath(),"src", "Database", "Migrations");
    if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
        $migrationsFolder = "/" . $migrationsFolder;
    }

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
$migrationTableExists = $dbHelper->doesTableExist('spawn_migrations');

$executedMigrations = [];
if($migrationTableExists) {
    //load executed migrations
    $erg = $dbHelper->query("SELECT * FROM spawn_migrations");

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
        if($e instanceof \spawn\system\Throwables\ClassNotFoundException) {
            throw $e;
        }


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
$sql = "INSERT INTO `spawn_migrations` (`class`,`timestamp`) VALUES ";
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
