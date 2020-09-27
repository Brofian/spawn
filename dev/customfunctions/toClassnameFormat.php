<?php

function toClassnameFormat(string $string) {
    $chars = str_split($string);

    $nextBig = true;
    foreach($chars as &$char) {
        if($nextBig) {
            $char = strtoupper($char);
            $nextBig = false;

            continue;
        }

        if($char == '_') {
            $char = '';
            $nextBig = true;
        }

    }

    return implode('',$chars);


}