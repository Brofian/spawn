<?php

namespace webu\system\Core\Helper\FrameworkHelper;


class CUriConverter {


    public static function cUriToUri(string $cUri, array $parameters) {

        foreach($parameters as $key => $value) {
            $cUri = str_replace("{".$key."}", $value, $cUri);
        }

        return $cUri;
    }

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