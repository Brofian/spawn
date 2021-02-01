<?php


namespace src\Models;

class SidebarElement {

    /** @var array  */
    private $children = [];
    /** @var string  */
    private $title = "";
    /** @var string  */
    private $url = "";
    /** @var string */
    private $icon = "";
    /** @var string */
    private $action = "";
    /** @var int  */
    private $layer = 0;
    /** @var string  */
    private $color = "";

    /**
     * SidebarElement constructor.
     * @param string $title
     * @param string $url
     * @param string $iconClass
     * @param string $action
     * @param string $color
     */
    public function __construct(string $title, string $url = "", string $iconClass = "", string $action = "", string $color = "")
    {
        $this->title = $title;
        $this->url = $url;
        $this->icon = $iconClass;
        $this->action = $action;
        $this->color = $color;
    }



    /**
     * @param SidebarElement $child
     */
    public function addChild(SidebarElement $child) {
        $child->setLayer($this->layer+1);
        if($child->getColor() == "") {
            $child->setColor($this->color);
        }
        $this->children[] = $child;
    }

    /**
     * @param int $layer
     */
    public function setLayer(int $layer) {
        $this->layer = $layer;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title) {
        $this->title = $title;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url) {
        $this->url = $url;
    }

    /**
     * @param string $iconClass
     */
    public function setIcon(string $iconClass) {
        $this->icon = $iconClass;
    }

    /**
     * @param string $iconClass
     */
    public function setColor(string $color) {
        $this->color = $color;
    }

    /**
     * @param string $iconClass
     */
    public function setAction(string $action) {
        $this->action = $action;
    }




    /**
     * @return int
     */
    public function getLayer() {
        return $this->layer;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getIcon() {
        return $this->icon;
    }

    /**
     * @return string
     */
    public function getColor() {
        return $this->color;
    }

    /**
     * @return string
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getChildren() {
        return $this->children;
    }

}