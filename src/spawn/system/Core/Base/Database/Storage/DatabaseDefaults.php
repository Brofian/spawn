<?php

namespace spawn\system\Core\Base\Database\Storage;

class DatabaseDefaults
{

    const NULL = "NULL";
    const CURRENT_TIMESTAMP = "CURRENT_TIMESTAMP";

    /**
     * @param $string
     * @return string
     */
    public static function stringToDefault($string): string
    {
        return "'" . $string . "'";
    }


}