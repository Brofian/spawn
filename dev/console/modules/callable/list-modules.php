<?php

use webu\system\Core\Contents\Modules\ModuleLoader;


if(!isset($moduleCollection)) {
    $moduleLoader = new ModuleLoader();
    $moduleCollection = $moduleLoader->loadModules();
}

return $moduleCollection;
