<?php declare(strict_types=1);


use bin\spawn\IO;

$background = IO::BLUE_BG;
$text = IO::LIGHT_CYAN_TEXT;



IO::printLine(PHP_EOL, $background);


IO::printLine(IO::TAB . "   _____   ____    ___   _       __  _   __", $text);
IO::printLine(IO::TAB . "  / ___/  / __ \  /   | | |     / / / | / /", $text);
IO::printLine(IO::TAB . "  \__ \  / /_/ / / /| | | | /| / / /  |/ / ", $text);
IO::printLine(IO::TAB . " ___/ / / ____/ / ___ |_| |/ |/ / / /|  /  ", $text);
IO::printLine(IO::TAB . "/____(_)_/   (_)_/  |_(_)__/|__(_)_/ |_(_) ", $text);
IO::printLine(IO::TAB . "-> Standard PHP Application without Norms <-", $text);
IO::printLine(IO::TAB . "-> A Framework made by Fabian Holzwarth   <-", $text);

IO::endLine(IO::BLACK_BG);
IO::endLine();
IO::endLine();


