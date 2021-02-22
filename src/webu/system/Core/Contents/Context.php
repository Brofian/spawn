<?php

namespace webu\system\Core\Contents;

class Context
{

    /** @var array $context */
    private $context = array();
    /** @var bool $isBackendContext */
    private $isBackendContext = false;

    //Fix array index that are always accessible
    const INDEX_USER = 'user';

    /**
     * @param string $name
     * @param mixed $variable
     */
    public function set(string $name, $variable)
    {
        $this->context[$name] = $variable;
    }


    /**
     * @param array $entries
     */
    public function multiSet(array $entries)
    {
        foreach ($entries as $name => $variable) {
            $this->context[$name] = $variable;
        }
    }

    /**
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }


    /**
     * @param bool $isBackendContext
     */
    public function setBackendContext($isBackendContext = true)
    {
        $this->isBackendContext = $isBackendContext;
    }

    /**
     * @return bool
     */
    public function getBackendContext()
    {
        return $this->isBackendContext;
    }

    /**
     * @param string $identifier
     * @param bool $fallback
     * @return bool|mixed
     */
    public function get(string $identifier, $fallback = false)
    {
        if ($identifier == '' || isset($this->context[$identifier])) {
            return $this->context[$identifier];
        } else {
            return $fallback;
        }
    }

}