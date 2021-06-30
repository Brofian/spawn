<?php declare(strict_types=1);


use bin\webu\IO;

$background = IO::BLUE_BG;
$text = IO::LIGHT_CYAN_TEXT;



IO::printLine(PHP_EOL, $background);

IO::printLine(IO::TAB . " _       __     __", $text);
IO::printLine(IO::TAB . "| |     / /__  / /_  __  __", $text);
IO::printLine(IO::TAB . "| | /| / / _ \/ __ \/ / / /", $text);
IO::printLine(IO::TAB . "| |/ |/ /  __/ /_/ / /_/ /", $text);
IO::printLine(IO::TAB . "|__/|__/\___/_.___/\__,_/", $text);
IO::printLine(IO::TAB . "-> A PHP Framework <-", $text);
IO::printLine(IO::TAB . "-> Made by Fabian Holzwarth <-", $text);

IO::endLine(IO::BLACK_BG);
IO::endLine();
IO::endLine();


