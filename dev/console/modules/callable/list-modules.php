<?php

use webu\system\Core\Contents\Modules\ModuleLoader;
use webu\system\Core\Base\Helper\DatabaseHelper;
use \bin\webu\IO;

if(!isset($moduleCollection)) {
    $dbHelper = new DatabaseHelper();
    $moduleLoader = new ModuleLoader();
    $moduleCollection = $moduleLoader->readModules($dbHelper->getConnection());

}

return $moduleCollection;
