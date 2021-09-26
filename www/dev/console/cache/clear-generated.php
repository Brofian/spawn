<?php declare(strict_types=1);

use bin\spawn\IO;
use spawn\system\Core\Helper\URIHelper;

$cacheDir = ROOT.CACHE_DIR;
URIHelper::pathifie($cacheDir, DIRECTORY_SEPARATOR);
$cacheDir = URIHelper::createPath(
    [
        $cacheDir,
        "private",
        "generated"
    ]
);


IO::printLine("> Clearing generated Files cache", IO::YELLOW_TEXT);

rrmdir($cacheDir);

IO::printLine("> - Successfully cleared", IO::LIGHT_GREEN_TEXT);