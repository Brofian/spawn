<?php

namespace spawn\Core\Base\Database\Definition\TableDefinition\DefaultColumns;


use spawn\system\Core\Base\Database\Definition\TableDefinition\AbstractColumn;
use spawn\system\Core\Base\Database\Definition\TableDefinition\Constants\ColumnTypes;

class IntColumn extends AbstractColumn {

    public const SMALL_INT = 0;
    public const DEFAULT_INT = 1;
    public const BIG_INT = 2;

    protected string $columnName;
    protected ?bool $canBeNull;
    protected ?int $default;
    protected int $intType;
    protected ?bool $autoIncrement;
    protected ?bool $unsigned;


    public function __construct(
        string $columnName,
        int $intType = self::DEFAULT_INT,
        ?bool $canBeNull = null,
        ?int $default = null,
        ?bool $autoIncrement = null,
        ?bool $unsigned = null
    )
    {
        $this->columnName = $columnName;
        $this->canBeNull = $canBeNull;
        $this->default = $default;
        $this->intType = $intType;
        $this->autoIncrement = $autoIncrement;
        $this->unsigned = $unsigned;
    }


    public function getName(): string
    {
        return $this->columnName;
    }

    public function getType(): string
    {
        switch($this->intType) {
            case self::SMALL_INT:
                return ColumnTypes::SMALL_INT;
            case self::BIG_INT:
                return ColumnTypes::BIG_INT;
            default:
                return ColumnTypes::INTEGER;
        }
    }

    public function canBeNull(): ?bool
    {
        return $this->canBeNull;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function isAutoIncrement(): ?bool
    {
        return $this->autoIncrement;
    }

    public function isPrimaryKey(): bool
    {
        return $this->autoIncrement;
    }

    public function isUnsigned(): ?bool
    {
        return $this->unsigned;
    }


}