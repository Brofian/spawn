<?php

namespace webu\system\Core\Base\Extensions\Twig;


use Twig\Environment;
use Twig\TwigFunction;

abstract class FunctionExtension {

    public function addToTwig(Environment &$twig) {
        $function = new TwigFunction(
            $this->getFunctionName(),
            $this->getFunctionFunction(),
            $this->getFunctionoptions()
        );

        $twig->addFunction($function);
    }


    abstract protected function getFunctionName() : string;

    abstract protected function getFunctionFunction() : callable;

    abstract protected function getFunctionOptions() : array;



}