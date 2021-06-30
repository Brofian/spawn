<?php declare(strict_types=1);

use \webu\system\Core\Helper\URIHelper;
use \bin\webu\IO;

$cacheDir = ROOT.CACHE_DIR;
URIHelper::pathifie($cacheDir, DIRECTORY_SEPARATOR);
$cacheDir = URIHelper::createPath(
    [
        $cacheDir,
        "private",
        "twig"
    ]
);


IO::printLine("> Clearing twig cache", IO::YELLOW_TEXT);

rrmdir($cacheDir);

IO::printLine("> - Successfully cleared", IO::LIGHT_GREEN_TEXT);
