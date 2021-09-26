<?php declare(strict_types=1);

namespace spawn\system\Core\Helper;

use spawn\system\Core\Base\Custom\FileEditor;
use spawn\system\Core\Contents\XMLContentModel;

class XMLReader {



    public static function readFile(string $filePath) : XMLContentModel  {
        $xml = FileEditor::getFileContent(URIHelper::pathifie($filePath));

        if($xml === false) {
            return new XMLContentModel("empty");
        }

        $xmlContent = simplexml_load_string($xml);

        $rootContainer = new XMLContentModel($xmlContent->getName());

        $rootContainer->loadFromSimpleXMLElement($xmlContent, $filePath);

        return $rootContainer;
    }




}