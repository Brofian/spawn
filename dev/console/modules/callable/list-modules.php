<?php declare(strict_types=1);

use spawn\system\Core\Contents\Modules\ModuleLoader;
use spawn\system\Core\Base\Helper\DatabaseHelper;
use \bin\spawn\IO;

if(!isset($moduleCollection)) {
    $dbHelper = new DatabaseHelper();
    $moduleLoader = new ModuleLoader();
    $moduleCollection = $moduleLoader->readModules($dbHelper->getConnection());

}

return $moduleCollection;
