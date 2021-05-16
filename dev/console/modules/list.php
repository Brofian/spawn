<?php

use bin\webu\IO;
use webu\system\Core\Contents\Modules\ModuleCollection;
use webu\system\Core\Contents\Modules\Module;

/** @var ModuleCollection $moduleCollection */
$moduleCollection = include(__DIR__ . "/callable/list-modules.php");

$moduleList = [];
$moduleList[] = [
    "ID",
    "Module",
    "Active",
    "Information"
];


/** @var Module $module */
foreach($moduleCollection->getModuleList() as $module) {
    $moduleList[] = [
        $module->getId(),
        $module->getName(),
        $module->isActive(),
        json_encode($module->getInformation())
    ];
}


IO::printAsTable($moduleList, true);