<?php

namespace webu\system\Core\Helper\FrameworkHelper;


class CUriConverter {


    public static function cUriToUri(string $cUri, array $parameters) {

        foreach($parameters as $key => $value) {
            $cUri = str_replace("{".$key."}", $value, $cUri);
        }

        return $cUri;
    }

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



    public static function getParametersFromUri(string $uri, string $curi, array $uriVars = []) : array {
        $uri = "/" . $uri;

        $matches = [];
        preg_match_all($curi, $uri, $matches);


        $parameters = [];
        for($i = 1; $i < sizeof($matches); $i++) {
            $parameters[] = $matches[$i][0];
        }

        return $parameters;
    }

}