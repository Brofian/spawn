<?php

namespace system\Core\Base\Database\Definition\TableDefinition\Constants;

class ColumnDefaults
{

    public const NONE = null;

    public const CURRENT_TIMESTAMP = 'CURRENT_TIMESTAMP';

    public const NULL = 'NULL';

    /**
     * @param string|int $value
     * @return mixed
     */
    public static function VALUE($value)
    {
        if (is_integer($value) || is_string($value)) {
            return $value;
        }

        return null;
    }


}