<?php

namespace webu\system\Core\Base\Extensions\Twig;


use Twig\Environment;
use Twig\TwigFilter;

abstract class FilterExtensionInterface {

    public function __construct(Environment &$twig) {
        $filter = new TwigFilter(
            $this->getFilterName(),
            $this->getFilterFunction()
        );

        $twig->addFilter($filter);
    }


    abstract protected function getFilterName() : string;

    abstract protected function getFilterFunction() : callable;

}