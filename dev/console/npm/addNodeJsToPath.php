<?php

use bin\webu\IO;

const DS = DIRECTORY_SEPARATOR;
$newPathVars = [
    ROOT.DS."vendor".DS."nodejs".DS."nodejs",
    ROOT.DS."vendor".DS."nodejs".DS."nodejs".DS."bin",
    ROOT.DS."vendor".DS."bin"
];

foreach($newPathVars as $newPath) {
    IO::execInDir('export PATH=$PATH:'.$newPath, ROOT);
}



IO::printLine("-> Added node to PATH");