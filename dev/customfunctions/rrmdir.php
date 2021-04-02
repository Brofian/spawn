<?php

function rrmdir($dir, $followSymlinks=false) {
    if(!file_exists($dir) || !is_dir($dir)) return false;

    $files = array_diff(scandir($dir), array('.','..'));

    foreach ($files as $file) {
        $target = "$dir/$file";
        if(is_dir($target) && ($followSymlinks || is_link($target) == false)) {
            rrmdir($target);
        }
        else if($followSymlinks || is_link($target) == false) {
            unlink($target);
        }
    }

    return rmdir($dir);
}