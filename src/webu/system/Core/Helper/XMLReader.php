<?php declare(strict_types=1);

namespace webu\system\Core\Helper;

use webu\system\Core\Base\Custom\FileEditor;
use webu\system\Core\Contents\XMLContentModel;

class XMLReader {



    public static function readFile(string $filePath) : XMLContentModel  {
        $xml = FileEditor::getFileContent(URIHelper::pathifie($filePath));


        if(!$xml) return new XMLContentModel("empty");

        $xmlContent = simplexml_load_string($xml);

        $rootContainer = new XMLContentModel($xmlContent->getName());

        $rootContainer->loadFromSimpleXMLElement($xmlContent, $filePath);

        return $rootContainer;
    }




}