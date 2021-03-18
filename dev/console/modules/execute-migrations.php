<?php

use \webu\system\core\base\Migration;
use \bin\webu\IO;

/*
 *
 * Load all Migration files
 *
 */

$filecrawler = new \webu\system\Core\Base\Custom\FileCrawler();
$migrations = $filecrawler->searchInfos(ROOT, function(
    string $fileContent,
    array &$currentResults,
    string $fileName,
    string $rootPath,
    string $relativePath
) {

    $matches = [];
    //split up the word class, namespace and migration to prevent this file from being autoloaded incorrectly
    $isMigration = preg_match_all('/clas'.'s ([^{]*) extends Migr'.'ation/m', $fileContent, $matches);

    if(!$isMigration || count($matches) < 2) {
        return;
    }
    $className = $matches[1][0];


    $matches = [];
    //split up the word class, namespace and migration to prevent this file from being autoloaded incorrectly
    $hasNamespace = preg_match_all('/name'.'space ([^;]*);/m', $fileContent, $matches);
    if(!$hasNamespace || count($matches) < 2) {
        return;
    }
    $namespace = $matches[1][0];

    /** @var Migration|string $fullClassName */
    $fullClassName = $namespace . "\\" . $className;

    $currentResults[] = [$fullClassName::getUnixTimestamp(),$fullClassName];

});


//sort all migrations by their timestamp (0 -> lowest)
usort($migrations, function($a, $b) {
    return ($a[0] < $b[0]) ? -1 : 1;
});




/*
 *
 * Get already executed Migrations
 *
 */


$dbHelper = new \webu\system\Core\Base\Helper\DatabaseHelper();
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
    }
    catch(Exception $e) {
        IO::print("An error occured while running Migration ", IO::RED_TEXT);
        IO::print($migrationClass, IO::YELLOW_TEXT);
        IO::printLine("! Skipping!", IO::RED_TEXT);
        $problems++;
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
