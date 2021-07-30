<?php

namespace spawn\Core\Base\Database\Definition\TableDefinition\DefaultColumns;


use spawn\system\Core\Base\Database\Definition\TableDefinition\AbstractColumn;
use spawn\system\Core\Base\Database\Definition\TableDefinition\Constants\ColumnTypes;

class UpdatedAtColumn extends AbstractColumn {


    public function getName(): string
    {
        return 'updatedAt';
    }

    public function getType(): string
    {
        return ColumnTypes::DATETIME_TZ;
    }

    public function canBeNull(): ?bool
    {
        return false;
    }


}