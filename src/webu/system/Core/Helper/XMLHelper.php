<?php


namespace webu\system\Core\Helper;


class XMLHelper
{

    public static function readFile(string $path)
    {
        return simplexml_load_file($path);
    }


}