<?php

use bin\webu\IO;
use webu\system\Core\Contents\Modules\Module;
use webu\system\Core\Helper\FrameworkHelper\ResourceCollector;


$moduleCollection = include(__DIR__ . "/callable/list-modules.php");

if(!isset($filesGathered) || !$filesGathered) {
    IO::printLine("> gathering files from modules...", IO::YELLOW_TEXT);

    /** @var Module $module */
    foreach($moduleCollection->getModuleList() as $module) {
        IO::printLine(IO::TAB . "- " . $module->getName());
    }

    try {
        $resourceCollector = new ResourceCollector();
        $resourceCollector->gatherModuleData($moduleCollection);

        $filesGathered = true;

        IO::printLine("> - successfully gathered files", IO::GREEN_TEXT);
    }
    catch(Exception $e) {
        IO::printLine("> - failed gathering files! There is probably more output above", IO::RED_TEXT);
        die();
    }


}


