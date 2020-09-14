<?php

namespace webu\system\Core\Base\Database\Storage;

class DatabaseDefaults
{

    const NULL = "NULL";
    const CURRENT_TIMESTAMP = "CURRENT_TIMESTAMP";

    public static function stringToDefault($string): string
    {
        return "'" . $string . "'";
    }


}