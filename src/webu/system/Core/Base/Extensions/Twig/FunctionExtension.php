<?php

namespace webu\system\Core\Base\Extensions\Twig;


use Twig\Environment;
use Twig\TwigFunction;

abstract class FunctionExtension {

    /**
     * @param Environment $twig
     */
    public function addToTwig(Environment &$twig) {
        $function = new TwigFunction(
            $this->getFunctionName(),
            $this->getFunctionFunction(),
            $this->getFunctionoptions()
        );

        $twig->addFunction($function);
    }

    /**
     * @return string
     */
    abstract protected function getFunctionName() : string;

    /**
     * @return callable
     */
    abstract protected function getFunctionFunction() : callable;

    /**
     * @return array
     */
    abstract protected function getFunctionOptions() : array;



}