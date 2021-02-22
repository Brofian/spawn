<?php


namespace webu\system\Core\Helper;


class URIHelper
{

    const DEFAULT_SEPERATOR = "\\";

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
     * @return string
     */
    public static function pathifie(string &$string, string $seperator = self::DEFAULT_SEPERATOR): string
    {
        $string = str_replace(self::SEPERATORS, $seperator, $string);

        $string = trim($string, implode("", self::SEPERATORS));

        return $string;
    }

    /**
     * @param string $p1
     * @param string $p2
     * @param string $seperator
     * @return string
     */
    public static function joinPaths(string $p1, string $p2, $seperator = self::DEFAULT_SEPERATOR)
    {
        $p1 = trim($p1, implode("", self::SEPERATORS));
        $p2 = trim($p2, implode("", self::SEPERATORS));

        $joined = $p1 . "/" . $p2;

        self::pathifie($joined, $seperator);

        return $joined;
    }



    public static function urifie(string &$string) {
        self::pathifie($string, "/");
        $string = urlencode($string);
        return $string;
    }
}