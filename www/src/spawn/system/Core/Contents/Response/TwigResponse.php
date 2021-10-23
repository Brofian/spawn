<?php

namespace spawn\system\Core\Contents\Response;

use spawn\system\Core\Helper\TwigHelper;
use spawn\system\Core\Services\ServiceContainerProvider;

class TwigResponse extends AbstractResponse {

    protected TwigHelper $twig;

    protected string $renderFilePath = 'base.html.twig';

    public function __construct(string $renderFilePath)
    {
        $this->twig = ServiceContainerProvider::getServiceContainer()->getServiceInstance('system.twig.helper');
        parent::__construct('');
    }


    public function getResponse(): string {
        return $this->twig->render($this->renderFilePath);
    }

}