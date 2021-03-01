<?php

namespace webu\system\Core\Base\Extensions\Twig;


use Twig\Environment;
use Twig\TwigFilter;

abstract class FilterExtension {


    public function addToTwig(Environment &$twig) {
        $filter = new TwigFilter(
            $this->getFilterName(),
            $this->getFilterFunction(),
            $this->getFilteroptions()
        );

        $twig->addFilter($filter);
    }


    abstract protected function getFilterName() : string;

    abstract protected function getFilterFunction() : callable;

    abstract protected function getFilterOptions() : array;



}