<?php

function parentDir(string $path, int $steps = 1) {
    if($steps <= 0) return $path;

    for($i = 0; $i < $steps; $i++) {
        $path = dirname($path);
    }

    return $path;
}