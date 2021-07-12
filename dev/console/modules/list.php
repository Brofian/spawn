<?php

use bin\spawn\IO;
use spawn\system\Core\Contents\Modules\ModuleCollection;
use spawn\system\Core\Contents\Modules\Module;

/** @var ModuleCollection $moduleCollection */
$moduleCollection = include(__DIR__ . "/callable/list-modules.php");

$moduleList = [];
$moduleList[] = [
    "ID",
    "Module",
    "Active",
    "Version",
    "Author"
];


/** @var Module $module */
foreach($moduleCollection->getModuleList() as $module) {
    $moduleList[] = [
        $module->getId(),
        $module->getSlug(),
        $module->isActive() ? "1" : "0",
        $module->getInformation("version"),
        $module->getInformation("author")
    ];
}


IO::printAsTable($moduleList, true);