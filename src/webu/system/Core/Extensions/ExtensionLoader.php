<?php

namespace webu\system\Core\Extensions;

use Twig\Environment;
use webu\system\Core\Base\Custom\FileCrawler;
use webu\system\Core\Base\Extensions\Twig\FilterExtension;
use webu\system\Core\Base\Extensions\Twig\FunctionExtension;
use webu\system\Core\Extensions\Twig\AssetFunctionExtension;
use webu\system\Core\Extensions\Twig\DumpFunctionExtension;
use webu\system\Core\Extensions\Twig\HashFilterExtension;
use webu\system\Core\Extensions\Twig\IconFilterExtension;
use webu\system\Core\Extensions\Twig\LinkFilterExtension;
use webu\system\Core\Helper\XMLHelper;


class ExtensionLoader {

    public static function loadTwigExtensions(Environment &$twig) {

        /*
         * Filter
         */
        $hashFilter = new HashFilterExtension();
        $hashFilter->addToTwig($twig);

        $iconFilter = new IconFilterExtension();
        $iconFilter->addToTwig($twig);

        $linkFilter = new LinkFilterExtension();
        $linkFilter->addToTwig($twig);




        /*
         * Functions
         */
        $assetFunction = new AssetFunctionExtension();
        $assetFunction->addToTwig($twig);

        $dumpFunction = new DumpFunctionExtension();
        $dumpFunction->addToTwig($twig);


        /*
         * Tags
         */


        return true;
    }
}