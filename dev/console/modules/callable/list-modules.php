<?php

use webu\system\Core\Contents\Modules\ModuleLoader;
use webu\system\Core\Base\Helper\DatabaseHelper;
use \bin\webu\IO;

if(!isset($moduleCollection)) {
    $dbHelper = new DatabaseHelper();
    $moduleLoader = new ModuleLoader();
    $moduleCollection = $moduleLoader->loadModules($dbHelper->getConnection());


    if(count($moduleCollection->getModuleList()) < 1) {
        IO::print("Modules are not laoded yet! Please run ", IO::RED_TEXT);
        IO::print("bin/console modules:refresh", IO::YELLOW_TEXT);
        IO::print(" to load the modules to the database! Now loading modules manually...", IO::RED_TEXT);

        $moduleCollection = $moduleLoader->readModules();
    }
}

return $moduleCollection;
