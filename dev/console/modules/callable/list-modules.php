<?php

use webu\system\Core\Contents\Modules\ModuleLoader;
use webu\system\Core\Base\Helper\DatabaseHelper;

if(!isset($moduleCollection)) {
    $dbHelper = new DatabaseHelper();
    $moduleLoader = new ModuleLoader();
    $moduleCollection = $moduleLoader->loadModules($dbHelper->getConnection());
}

return $moduleCollection;
