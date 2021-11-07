<?php

use bin\spawn\IO;
use spawn\system\Core\Services\ServiceContainer;
use spawn\system\Core\Services\ServiceContainerProvider;
use spawnApp\Services\SeoUrlManager;

/** @var ServiceContainer $serviceContainer */
$serviceContainer = ServiceContainerProvider::getServiceContainer();
/** @var SeoUrlManager $seoUrlManager */
$seoUrlManager = $serviceContainer->getServiceInstance('system.service.seo_url_manager');

IO::printLine('> Adding new available Methods and removing stale ones', IO::YELLOW_TEXT);

$result = $seoUrlManager->refreshSeoUrlEntries(true);

IO::printLine('> Added '.$result['added'].' Methods', IO::GREEN_TEXT);
if(isset($result['removed'])) {
    IO::printLine('> Removed '.$result['removed'].' Methods', IO::GREEN_TEXT);
}


