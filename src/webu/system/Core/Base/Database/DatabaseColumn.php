<?php


namespace webu\system\Core\Base\Database;


use webu\system\Core\Base\Database\Storage\DatabaseAttributes;
use webu\system\Core\Base\Database\Storage\DatabaseType;

class DatabaseColumn
{
    const NAME = '';

    /** @var string */
    protected $name = '';
    /** @var string */
    protected $type = DatabaseType::INT;
    /** @var int */
    protected $length = 0;
    /** @var string|null */
    protected $default = null;
    /** @var bool */
    protected $canBeNull = true;
    /** @var string */
    protected $index = '';
    /** @var bool */
    protected $autoIncrement = false;
    /** @var string */
    protected $attribute = DatabaseAttributes::NONE;


    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;

        return $this;
    }


    /**
     * Returns the sql for this column
     * @return string
     */
    public function getColumnCreationSQL(): string
    {
        $sql = '`' . $this->name . '`';
        $sql .= ' ' . $this->type;

        if ($this->length > 0) {
            $sql .= '(' . $this->length . ')';
        }

        if ($this->attribute != '') {
            $sql .= ' ' . $this->attribute;
        }

        if ($this->canBeNull) {
            $sql .= ' NULL';
        } else {
            $sql .= ' NOT NULL';
        }

        if ($this->default != null) {
            $sql .= ' DEFAULT ' . $this->default;
        }

        if ($this->autoIncrement) {
            $sql .= ' AUTO_INCREMENT';
        }

        return $sql;

    }


    public function setLength(int $length)
    {
        $this->length = $length;
        return $this;
    }

    public function setAutoIncrement(bool $autoIncrement)
    {
        $this->autoIncrement = $autoIncrement;
        return $this;
    }

    public function setCanBeNull(bool $canBeNull)
    {
        $this->canBeNull = $canBeNull;
        return $this;
    }

    public function setAttribute(string $attribute)
    {
        $this->attribute = $attribute;
        return $this;
    }

    public function setDefault($default)
    {
        $this->default = $default;
        return $this;
    }

    public function setIndex(string $index)
    {
        $this->index = $index;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @return string
     */
    public function getDefault(): string
    {
        return $this->default;
    }

    /**
     * @return bool
     */
    public function getCanBeNull(): bool
    {
        return $this->canBeNull;
    }

    /**
     * @return string
     */
    public function getIndex(): string
    {
        return $this->index;
    }

    /**
     * @return bool
     */
    public function getAutoIncrement(): bool
    {
        return $this->autoIncrement;
    }

    /**
     * @return string
     */
    public function getAttribute(): string
    {
        return $this->attribute;
    }


}