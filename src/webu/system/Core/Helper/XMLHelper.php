<?php


namespace webu\system\Core\Helper;


class XMLHelper
{

    /** @var string  */
    private $filePath = "";
    /** @var string  */
    private $folderPath = "";


    public function readFile(string $path)
    {
        $this->filePath = $path;



        $pathSplit = explode(DIRECTORY_SEPARATOR, URIHelper::pathifie($path, DIRECTORY_SEPARATOR, false));
        if(count($pathSplit) > 1) array_pop($pathSplit);
        $this->folderPath = implode(DIRECTORY_SEPARATOR, $pathSplit);


        $xmlObject = $this->loadFile($this->filePath);

        $this->searchLinks($xmlObject);


        return $xmlObject;
    }


    private function loadFile($path) {
        $xmlObject = \simplexml_load_file($path);

        return $xmlObject;
    }


    /**
     * @param \SimpleXMLElement $xmlObject
     */
    private function searchLinks($xmlObject) {



        foreach($xmlObject->children() as $key => $child) {

            if(isset($child->link)) {

                $linkXML = $this->loadFile(URIHelper::joinPaths($this->folderPath, (string)$child->link));

                //replace
                /** @var \DOMElement $to */
                $to = dom_import_simplexml($child);
                $from = dom_import_simplexml($linkXML);
                $to->parentNode->replaceChild($to->ownerDocument->importNode($from, true), $to);
                unset($child->link);
            }


            $this->searchLinks($child);
        }


    }



}