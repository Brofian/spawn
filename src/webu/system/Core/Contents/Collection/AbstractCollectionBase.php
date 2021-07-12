<?php

namespace webu\system\Core\Contents\Collection;

use Iterator;
use Countable;

abstract class AbstractCollectionBase implements Iterator, Countable {

    protected array $collection = array();
    protected int $position;

    protected abstract function getByIndex(int $index);

    protected abstract function getCurrentKey();



    /*
     *
     * Iterator Functions
     *
     */

    public function current()
    {
        return $this->getByIndex($this->position);
    }

    public function next()
    {
        $this->position++;
    }

    public function key()
    {
        return $this->getCurrentKey();
    }

    public function valid()
    {
        return isset($this->collection[$this->getCurrentKey()]);
    }

    public function rewind()
    {
        $this->position = 0;
    }


    /*
     *
     * Countable Functions
     *
     */

    public function count()
    {
        return count($this->collection);
    }

}