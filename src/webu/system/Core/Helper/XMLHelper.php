<?php


namespace webu\system\Core\Helper;


class XMLHelper
{

    /** @var string  */
    private $filePath = "";
    /** @var string  */
    private $folderPath = "";

    /**
     * @param string $path
     * @return \SimpleXMLElement
     */
    public function readFile(string $path)
    {
        if(!$path) return null;

        $this->filePath = $path;

        //remove the filename from the path
        $pathSplit = explode(DIRECTORY_SEPARATOR, URIHelper::pathifie($path, DIRECTORY_SEPARATOR, false));
        if(count($pathSplit) > 1) array_pop($pathSplit);
        $this->folderPath = implode(DIRECTORY_SEPARATOR, $pathSplit);

        $xmlObject = $this->loadFile($this->filePath);

        $this->searchLinks($xmlObject);

        return $xmlObject;
    }

    /**
     * @param $path
     * @return \SimpleXMLElement
     */
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