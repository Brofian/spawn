<?php


namespace webu\system\Core\Helper;


class URIHelper
{

    const DEFAULT_SEPERATOR = DIRECTORY_SEPARATOR;

    const SEPERATORS = [
        "/",
        "\\",
        "\/",
        " ",
        "\n"
    ];

    /**
     * @param string $string
     * @param string $seperator
     * @param string $trim
     * @return string
     */
    public static function pathifie(string &$string, string $seperator = self::DEFAULT_SEPERATOR, bool $trim = false): string
    {
        $string = str_replace(self::SEPERATORS, $seperator, $string);

        if($trim) {
            $string = trim($string, implode("", self::SEPERATORS));
        }


        return $string;
    }

    /**
     * @param string $p1
     * @param string $p2
     * @param string $seperator
     * @return string
     */
    public static function joinPaths(string $p1, string $p2, $seperator = self::DEFAULT_SEPERATOR, bool $trim = false)
    {
        if($trim) {
            $p1 = trim($p1, implode("", self::SEPERATORS));
        }
        $p2 = trim($p2, implode("", self::SEPERATORS));



        $joined = $p1 . "/" . $p2;

        self::pathifie($joined, $seperator, $trim);

        return $joined;
    }



    public static function urifie(string &$string) {
        self::pathifie($string, "/");
        $string = urlencode($string);
        return $string;
    }
}