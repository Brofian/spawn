<?php declare(strict_types=1);

namespace webu\system\Core\Contents;

use webu\system\Core\Base\Custom\FileEditor;
use webu\system\Core\Contents\Collection\Collection;
use webu\system\Core\Helper\URIHelper;
use webu\system\Core\Helper\XMLReader;

class XMLContentModel {

    protected array $attributes = array();
    protected string $type = "";
    protected ?string $value = null;
    protected array $children = array();

    public function __construct(string $type)
    {
        $this->type = $type;
    }


    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute(string $key): ?string
    {
        if(isset($this->attributes[$key])) {
            return $this->attributes[$key];
        }

        return null;
    }

    public function addAttribute(string $key, string $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return XMLContentModel[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function getChildrenByType(string $key): Collection
    {
        $childrenWithTag = new Collection();
        foreach($this->getChildren() as $child) {
            if($child->type == $key) {
                $childrenWithTag->add($child);
            }
        }

        return $childrenWithTag;
    }

    public function addChild(XMLContentModel $child): self
    {
        $this->children[] = $child;
        return $this;
    }

    public function getValue() : ?string {
        return $this->value;
    }

    public function setValue(string $value) : self {
        $this->value = $value;
        return $this;
    }




    public function loadFromSimpleXMLElement(\SimpleXMLElement $simpleXMLElement, string $filePath) {

        foreach($simpleXMLElement->attributes() as $attribute) {
            $this->addAttribute(
                $attribute->getName(),
                $attribute
            );
        }

        foreach($simpleXMLElement->children() as $key => $child) {

            if($key == 'import' && isset($child->attributes()["file"])) {

                $relPath = $child->attributes()["file"];

                $combinedPath = URIHelper::joinPaths(dirname($filePath), $relPath);

                $childXML = XMLReader::readFile($combinedPath);

                foreach($childXML->getChildren() as $cKey => $cChild) {
                    $this->addChild($cChild);
                }

            }
            else {
                $childXML = new XMLContentModel($key);

                $childXML->loadFromSimpleXMLElement($child, $filePath);

                if(count($childXML->getChildren()) < 1) {
                    $childXML->setValue($child[0]);
                }

                $this->addChild($childXML);
            }


        }

    }

}