<?php

use spawn\system\Core\Services\ServiceContainerProvider;
use spawn\system\Core\Services\ServiceContainer;
use spawnApp\Services\SeoUrlManager;
use bin\spawn\IO;

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


