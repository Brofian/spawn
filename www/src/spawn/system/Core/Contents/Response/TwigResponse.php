<?php

namespace spawn\system\Core\Contents\Response;

use spawn\system\Core\Helper\TwigHelper;
use spawn\system\Core\Services\ServiceContainerProvider;

class TwigResponse extends AbstractResponse {

    protected TwigHelper $twig;

    protected string $renderFilePath = 'base.html.twig';
    protected ?array $twigData = null;

    public function __construct(string $renderFilePath, ?array $twigData = null)
    {
        parent::__construct('');
        $this->twig = ServiceContainerProvider::getServiceContainer()->getServiceInstance('system.twig.helper');
        $this->renderFilePath = $renderFilePath;
        $this->twigData = $twigData;
    }


    public function getResponse(): string {
        return $this->twig->render($this->renderFilePath, $this->twigData);
    }

}