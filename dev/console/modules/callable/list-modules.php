<?php

use webu\system\Core\Contents\Modules\ModuleLoader;


if(!isset($moduleCollection)) {
    $moduleLoader = new ModuleLoader();
    $moduleCollection = $moduleLoader->loadModules(ROOT . "/modules");
}

return $moduleCollection;
