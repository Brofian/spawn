<?php

namespace spawn\Core\Base\Database\Definition\TableDefinition\DefaultColumns;


use spawn\system\Core\Base\Database\Definition\TableDefinition\AbstractColumn;
use spawn\system\Core\Base\Database\Definition\TableDefinition\Constants\ColumnTypes;

class DateTimeColumn extends AbstractColumn {

    protected string $name;
    protected bool $canBeNull;

    public function __construct(string $name, bool $canBeNull = true)
    {
        $this->name = $name;
        $this->canBeNull = $canBeNull;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return ColumnTypes::DATETIME_TZ;
    }

    public function canBeNull(): ?bool
    {
        return $this->canBeNull;
    }

    public function getTypeIdentifier()
    {
        return 'datetime'; //php´s \DateTime()
    }
}