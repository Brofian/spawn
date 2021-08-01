<?php

namespace spawn\Core\Base\Database\Definition\TableDefinition\DefaultColumns;


use spawn\system\Core\Base\Database\Definition\TableDefinition\AbstractColumn;
use spawn\system\Core\Base\Database\Definition\TableDefinition\Constants\ColumnTypes;

class JsonColumn extends AbstractColumn {

    protected string $columnName;
    protected bool $canBeNull;

    public function __construct(
        string $columnName,
        bool $canBeNull = true
    )
    {
        $this->columnName = $columnName;
        $this->canBeNull = $canBeNull;
    }


    public function getName(): string
    {
        return $this->columnName;
    }

    public function getType(): string
    {
        return ColumnTypes::JSON;
    }

    public function canBeNull(): ?bool
    {
        return $this->canBeNull;
    }


    public function getTypeIdentifier()
    {
        return \PDO::PARAM_STR;
    }
}