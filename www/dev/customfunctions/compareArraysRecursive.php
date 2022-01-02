<?php

function compareArraysRecursive(array $array1, array $array2): bool
{
    foreach($array1 as $key => $element) {
        //element is not set in another array
        if(!isset($array2[$key])) {
            return false;
        }
        $other = $array2[$key];

        if(get_debug_type($element) !== get_debug_type($other)) {
            return false;
        }

        if(is_object($element)) {
            if(get_class($element) !== get_class($other)) {
                return false;
            }
        }
        elseif(is_array($element) && is_array($other)) {
            if(!compareArraysRecursive($element, $other)) {
                return false;
            }
        }
        elseif(!($element === $other)) {
            return false;
        }

    }

    return true;
}