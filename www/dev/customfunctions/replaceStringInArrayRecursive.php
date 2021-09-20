<?php


function replaceStringInArrayRecursive(string $pattern, string $replacement, array &$subjectArray) {

    foreach($subjectArray as &$arrayChild) {

        if(is_array($arrayChild)) {
            replaceStringInArrayRecursive($pattern, $replacement, $arrayChild);
        }
        else if(is_string($arrayChild)) {
            $arrayChild = str_replace($pattern, $replacement, $arrayChild);
        }

    }

    return $subjectArray;
}