<?php

namespace webu\system\Core\Helper\FrameworkHelper;


class CUriConverter {

    public static function cUriToRegex(string $uri) {

        $pattern = "/{[^}]*}/m";
        preg_match_all($pattern, $uri, $matches);


        $uri = "^/" . trim($uri, "/ \n");

        foreach($matches[0] as $variable) {
            $uri = str_replace($variable, "([^/]*)", $uri);
        }

        $uri = str_replace("/", "\/", $uri);

        $uri = "/" . $uri . "$/m";

        return $uri;
    }

}