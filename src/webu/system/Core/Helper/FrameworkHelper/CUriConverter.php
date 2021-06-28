<?php

namespace webu\system\Core\Helper\FrameworkHelper;

/*
 * Custom Uri Converted
 * Allows reading and converting custom urls (like "/foo/bar/{id}/test")
 * to normal urls
 */
class CUriConverter {

    /**
     * @param string $cUri
     * @param array $parameters
     * @return string|string[]
     */
    public static function cUriToUri(string $cUri, array $parameters) {

        foreach($parameters as $key => $value) {
            $cUri = str_replace("{".$key."}", $value, $cUri);
        }

        return $cUri;
    }

    /**
     * @param string $uri
     * @param array $vars
     * @return string|string[]
     */
    public static function cUriToRegex(string $uri, array &$vars = []) {

        $pattern = "/{([^}]*)}/m";
        preg_match_all($pattern, $uri, $matches);

        $uri = "^/" . trim($uri, "/ \n");

        foreach($matches[0] as $variable) {
            $uri = str_replace($variable, "([^/]*)", $uri);
        }
        foreach($matches[1] as $variable_raw) {
            $vars[] = $variable_raw;
        }

        $uri = str_replace("/", "\/", $uri);

        $uri = "/" . $uri . "$/m";

        return $uri;
    }



    public static function getParametersFromUri(string $uri, string $curi) : array {
        $uri = "/" . $uri;

        $matches = [];
        preg_match_all($curi, $uri, $matches);

        $parameters = [];
        for($i = 1; $i < sizeof($matches); $i++) {
            $parameters[] = $matches[$i][0];
        }

        return $parameters;
    }


    public static function getParameterNames(string $c_uri): array {

        $pattern = "/{([^}]*)}/m";
        preg_match_all($pattern, $c_uri, $matches);

        if(!isset($matches[1])) return [];

        return $matches[1];
    }

}