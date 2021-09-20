<?php declare(strict_types=1);

use spawn\system\Core\Base\Helper\DatabaseHelper;
use spawn\system\Core\Contents\Modules\ModuleLoader;

if(!isset($moduleCollection)) {
    $dbHelper = new DatabaseHelper();
    $moduleLoader = new ModuleLoader();
    $moduleCollection = $moduleLoader->readModules($dbHelper->getConnection(), false);

}

return $moduleCollection;
