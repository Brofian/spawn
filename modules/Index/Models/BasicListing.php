<?php

namespace webu\modules\Index\Models;


class BasicListing {

    /** @var int  */
    private $maxPages = 0;
    /** @var int  */
    private $currentPage = 0;
    /** @var int  */
    private $entries = 0;
    /** @var int */
    private $itemsPerPage = 10;
    /** @var array  */
    private $elements = array();


    public function __construct()
    {}

    /**
     * @return int
     */
    public function getMaxPages(): int
    {
        return $this->maxPages;
    }

    /**
     * @param int $maxPages
     */
    public function setMaxPages(int $maxPages): void
    {
        $this->maxPages = $maxPages;
    }

    /**
     * @param bool $zeroIndexed
     * @return int
     */
    public function getCurrentPage(bool $zeroIndexed = false): int
    {
        if($this->currentPage > $this->maxPages) $this->currentPage = $this->maxPages;

        if($zeroIndexed)    return $this->currentPage-1;
        else                return $this->currentPage;
    }

    /**
     * @param int $currentPage
     */
    public function setCurrentPage(int $currentPage): void
    {
        if($currentPage < 1) $this->currentPage = 1;
        else                 $this->currentPage = $currentPage;
    }

    /**
     * @return int
     */
    public function getEntries(): int
    {
        return $this->entries;
    }

    /**
     * @param int $entries
     */
    public function setEntries(int $entries): void
    {
        $this->entries = $entries;
        $this->maxPages = ($entries<=0) ? 1 : ceil($entries / $this->itemsPerPage);
    }

    /**
     * @return array
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * @param mixed $elements
     */
    public function addElement($elements): void
    {
        $this->elements[] = $elements;
    }

    /**
     * @param array $elements
     */
    public function addElements(array $elements): void
    {
        $this->elements = array_merge($this->elements, $elements);
    }

    /**
     * @return int
     */
    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    /**
     * @param int $itemsPerPage
     */
    public function setItemsPerPage(int $itemsPerPage): void
    {
        if($itemsPerPage > 0) {
            $this->itemsPerPage = $itemsPerPage;
            $this->maxPages = ($this->entries<=0) ? 1 : ceil($this->entries / $this->itemsPerPage);
        }
    }



}
