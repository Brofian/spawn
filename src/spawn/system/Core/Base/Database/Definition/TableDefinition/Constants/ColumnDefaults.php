<?php

namespace system\Core\Base\Database\Definition\TableDefinition\Constants;

class ColumnDefaults {

    public const NONE = '';

    public const CURRENT_TIMESTAMP = 'DEFAULT CURRENT_TIMESTAMP';

    public const NULL = 'DEFAULT NULL';

    public static function VALUE($value): string {
        return "DEFAULT '$value'";
    }


}