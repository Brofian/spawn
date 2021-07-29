<?php

namespace spawn\Core\Base\Database\Definition\TableDefinition\DefaultColumns;


use spawn\system\Core\Base\Database\Definition\TableDefinition\AbstractColumn;
use spawn\system\Core\Base\Database\Definition\TableDefinition\Constants\ColumnTypes;

class StringColumn extends AbstractColumn {

    protected string $columnName;
    protected ?bool $canBeNull;
    protected ?string $default;
    protected ?int $length;
    protected ?bool $hasFixedLength;


    public function __construct(
        string $columnName,
        ?bool $canBeNull = null,
        ?string $default = null,
        ?int $maxLength = null,
        ?bool $hasFixedLength = false
    )
    {
        $this->columnName = $columnName;
        $this->canBeNull = $canBeNull;
        $this->default = $default;
        $this->hasFixedLength = $hasFixedLength;
        $this->length = $maxLength;
    }


    public function getName(): string
    {
        return $this->columnName;
    }

    public function getType(): string
    {
        return ($this->length !== null) ? ColumnTypes::STRING : ColumnTypes::TEXT;
    }

    public function hasFixedLength(): ?bool
    {
        return $this->hasFixedLength;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function canBeNull(): ?bool
    {
        return $this->canBeNull;
    }

    public function getLength(): ?int {
        return $this->length;
    }


}