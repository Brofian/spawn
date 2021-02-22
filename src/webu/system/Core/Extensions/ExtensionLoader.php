<?php

namespace webu\system\Core\Extensions;

use Twig\Environment;
use webu\system\Core\Base\Custom\FileCrawler;
use webu\system\Core\Base\Extensions\Twig\FilterExtension;
use webu\system\Core\Helper\XMLHelper;


class ExtensionLoader {

    public static function loadTwigExtensions(Environment &$twig) {

        $xmlHelper = new XMLHelper();
        $xml = $xmlHelper->readFile(__DIR__ . "\\Twig\\extensions.xml");

        foreach($xml->filters->filter as $filter) {
            /** @var FilterExtension $extensionClass */
            $cls = (string)($filter["class"]);
            $extensionClass = new $cls($twig);
        }


        foreach($xml->functions->function as $function) {
            /** @var FilterExtension $extensionClass */
            $cls = (string)($function["class"]);
            $extensionClass = new $cls($twig);
        }

        foreach($xml->tags->tag as $tag) {
            /** @var FilterExtension $extensionClass */
            $cls = (string)($tag["class"]);
            $extensionClass = new $cls($twig);
        }

        return true;
    }
}