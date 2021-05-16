<?php


use \webu\system\Core\Contents\Modules\Module;
use \bin\webu\IO;
use \webu\system\Core\Base\Custom\FileEditor;
use \webu\system\Core\Helper\URIHelper;

$moduleCollection = include(__DIR__ . "/../modules/callable/list-modules.php");


IO::printLine("Für welches Modul möchtest du die Migration erstellen?", IO::BLUE_TEXT);

$counter = 0;
$modules = [];

/** @var Module $module */
foreach($moduleCollection->getModuleList() as $module) {
    IO::printLine("[".$counter."] " . $module->getName());
    $modules[$counter] = $module;
    $counter++;
}


$answer = IO::readLine("Bitte gib eine gültige ID an: ", function($answer) use ($counter) {
    return (is_numeric($answer) && (int)$answer < $counter && (int)$answer >= 0);
});


$moduleName = $modules[$answer]->getName();
$path = URIHelper::joinMultiplePaths($modules[$answer]->getBasePath(), "src", "Database", "Migrations");

$try = 0;
do {
    $isValidAnswer = false;
    $answer = IO::readLine("Wie soll die Migration heißen? ");

    if(strlen($answer) < 3) {
        IO::printLine("Der Name muss mindestens aus 3 Zeichen bestehen!", IO::RED_TEXT);
    }
    else {
        $isValidAnswer = true;
    }


    if($try >= 5 && !$isValidAnswer) {
        IO::printLine("5 Fehlversuche! Vorgang wird abgebrochen!", IO::RED_TEXT);
        return;
    }
    $try++;
}
while(!$isValidAnswer);

$timeStamp = time();
$className = "M".$timeStamp.$answer;
$filePath = URIHelper::joinPaths($path, $className.".php");

FileEditor::createFile($filePath, "<?php

namespace modules\\".$moduleName."\\Database\\Migrations;

use webu\system\Core\Base\Helper\DatabaseHelper;
use webu\system\core\base\Migration;

class ".$className." extends Migr"."ation {
    
    public static function getUnixTimestamp(): int
    {
        //Do not edit this!
        return ".$timeStamp.";
    }

    function run(DatabaseHelper \$dbHelper)
    {
        //TODO: Add your Migration code here
    }

}
");


IO::print("Die Migration wurde erfolgreich erstellt unter ", IO::GREEN_TEXT);
IO::printLine($filePath, IO::LIGHT_GREEN_TEXT);