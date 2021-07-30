<?php declare(strict_types=1);

namespace spawn\system\Core\Extensions;

use spawn\system\Core\Base\Extensions\Twig\FilterExtension;
use spawn\system\Core\Base\Extensions\Twig\FunctionExtension;
use spawn\system\Core\Extensions\Twig\AssetFunctionExtension;
use spawn\system\Core\Extensions\Twig\DumpFunctionExtension;
use spawn\system\Core\Extensions\Twig\HashFilterExtension;
use spawn\system\Core\Extensions\Twig\IconFilterExtension;
use spawn\system\Core\Extensions\Twig\LinkFilterExtension;
use spawn\system\Core\Extensions\Twig\PreviewFilterExtension;
use spawn\system\Core\Services\ServiceContainerProvider;
use spawn\system\Core\Services\ServiceTags;
use Twig\Environment;


class ExtensionLoader {

    /**
     * @param Environment $twig
     * @return bool
     */
    public static function loadTwigExtensions(Environment &$twig) {

        //add twig extensions from modules
        $twigExtensions = ServiceContainerProvider::getServiceContainer()->getServicesByTag(ServiceTags::EXTENSION_TWIG);
        foreach($twigExtensions as $twigExtension) {
            /** @var FilterExtension|FunctionExtension $instance */
            $instance = $twigExtension->getInstance();
            $instance->addToTwig($twig);
        }

        /*
         * Filter
         */
        $hashFilter = new HashFilterExtension();
        $hashFilter->addToTwig($twig);

        $iconFilter = new IconFilterExtension();
        $iconFilter->addToTwig($twig);

        $linkFilter = new LinkFilterExtension();
        $linkFilter->addToTwig($twig);

        $previewFilter = new PreviewFilterExtension();
        $previewFilter->addToTwig($twig);


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