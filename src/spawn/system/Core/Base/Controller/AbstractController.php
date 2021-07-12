<?php declare(strict_types=1);

namespace spawn\system\Core\Base\Controller;


use spawn\system\Core\Helper\TwigHelper;
use spawn\system\Core\Services\ServiceContainer;
use spawn\system\Core\Services\ServiceContainerProvider;

abstract class AbstractController {

    protected ServiceContainer $container;
    protected TwigHelper $twig;

    public function __construct()
    {
        $this->container = ServiceContainerProvider::getServiceContainer();
        $this->twig = $this->container->getServiceInstance('system.twig.helper');
    }

}