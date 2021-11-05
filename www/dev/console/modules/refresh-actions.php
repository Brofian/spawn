<?php

use spawn\system\Core\Services\ServiceContainerProvider;
use spawn\system\Core\Services\ServiceContainer;
use spawnApp\Services\SeoUrlManager;



/** @var ServiceContainer $serviceContainer */
$serviceContainer = ServiceContainerProvider::getServiceContainer();
/** @var SeoUrlManager $seoUrlManager */
$seoUrlManager = $serviceContainer->getServiceInstance('system.service.seo_url_manager');

$seoUrlManager->refreshSeoUrlEntries(false);

