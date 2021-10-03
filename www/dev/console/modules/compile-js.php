<?php declare(strict_types=1);


use bin\spawn\IO;

//load resources from modules
include(__DIR__ . "/gather-files.php");

include_once(__DIR__ . "/../npm/addNodeJsToPath.php");

/** @var \spawn\system\Core\Contents\Modules\ModuleCollection $moduleCollection */
$moduleCollection = include(__DIR__ . "/callable/list-modules.php");
$code = 0;
$webpackDir = ROOT . "/src/npm";


//javascript kompilieren
IO::printLine("> compiling JavaScript", IO::YELLOW_TEXT);


$output = IO::execInDir("npx webpack --config webpack.config.js --progress", $webpackDir, false, $result, $code);

IO::printLine(IO::TAB . '- ' . $output);

if($code != 0) {
    IO::printLine(implode(PHP_EOL, $result), IO::RED_TEXT);

    IO::printLine("An Error occurred! There is probably more output above", IO::RED_TEXT);
    die();
}
else {
    IO::printLine("> - successfully compiled JavaScript", IO::GREEN_TEXT);
}
