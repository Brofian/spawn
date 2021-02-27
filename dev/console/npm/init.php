<?php

use bin\webu\IO;



if (IO::exec('npm -v') !== 0) {
    IO::printLine("Please install npm!", IO::RED_TEXT);
    exit();
}


IO::execInDir("npm install", ROOT . "/src/npm");



