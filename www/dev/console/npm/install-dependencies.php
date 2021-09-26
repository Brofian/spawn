<?php declare(strict_types=1);

use bin\spawn\IO;

IO::execInDir('composer run-script download-nodejs', ROOT);

include_once(__DIR__ . "/addNodeJsToPath.php");

//npx is installed as part of npm, which is installed as part of nodejs
IO::execInDir('npm install -g npx', ROOT);

IO::execInDir("npm install", ROOT . "/src/npm");
