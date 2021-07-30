<?php

namespace spawn\Core\Base\Database\Definition\TableDefinition\DefaultColumns;


use spawn\system\Core\Base\Database\Definition\TableDefinition\AbstractColumn;
use spawn\system\Core\Base\Database\Definition\TableDefinition\Constants\ColumnTypes;

class BooleanColumn extends AbstractColumn {

    protected string $columnName;
    protected bool $default;


    public function __construct(
        string $columnName,
        bool $default = false
    )
    {
        $this->columnName = $columnName;
        $this->default = $default;
    }


    public function getName(): string
    {
        return $this->columnName;
    }

    public function getType(): string
    {
        return ColumnTypes::BOOLEAN;
    }

    public function canBeNull(): ?bool
    {
        return true;
    }

    public function getDefault()
    {
        return ($this->default) ? 1 : 0;
    }

}