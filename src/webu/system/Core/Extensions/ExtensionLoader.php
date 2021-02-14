<?php

namespace webu\system\Core\Extensions;

use Twig\Environment;
use webu\system\Core\Base\Custom\FileCrawler;
use webu\system\Core\Base\Extensions\Twig\FilterExtensionInterface;
use webu\system\Core\Helper\XMLHelper;


class ExtensionLoader {

    public static function loadTwigExtensions(Environment &$twig) {

        $xml = XMLHelper::readFile(__DIR__ . "\\Twig\\extensions.xml");

        foreach($xml->filters->filter as $filter) {
            /** @var FilterExtensionInterface $extensionClass */
            $cls = (string)($filter["class"]);
            $extensionClass = new $cls($twig);
        }

        return true;
    }
}