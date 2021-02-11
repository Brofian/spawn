<?php


namespace webu\bin\console;

use webu\system\Core\Base\Custom\FileEditor;

class ConsoleIO {

    const COMMAND_ROOT = ROOT . "\\bin\\dev-ops";
    const IGNORED_DIRS = [
        '.',
        '..'
    ];

    const BLACK_TEXT = "\e[30m";
    const RED_TEXT = "\e[31m";
    const CYAN_TEXT = "\e[36m";
    const WHITE_TEXT = "\e[39m";

    const BLACK_BG = "\e[40m";
    const RED_BG = "\e[41m";
    const CYAN_BG = "\e[46m";
    const WHITE_BG = "\e[49m";

    const TAB = "   ";





    /** @var mixed|string  */
    private $command = "";
    /** @var array  */
    private $params = [];

    /** @var array  */
    private $commandList = [];

    public function __construct($arguments)
    {
        $this->command = array_shift($arguments);
        $this->params = $arguments;

        $this->commandList = $this->findCommandFiles(self::COMMAND_ROOT);
        $this->runCommand();
    }


    private function runCommand() {
        if(count($this->params) < 1) {
            echo "Please enter a command!" . PHP_EOL;
            $this->printAvailableCommands($this->commandList);
            return;
        }


        $path = explode(":",trim($this->params[0], ":"));

        $availableCommands = $this->commandList;
        $cmd = "";
        $parsedCmd = "";

        while(count($path) > 0) {

            $cmd = array_shift($path);

            if(isset($availableCommands[(string)$cmd])) {
                //known command


                if(is_array($availableCommands[(string)$cmd])) {
                    $availableCommands = $availableCommands[(string)$cmd];
                    $parsedCmd .= $cmd . ":";
                    continue;
                }
                else if(is_file($availableCommands[(string)$cmd])) {
                    include($availableCommands[(string)$cmd]);
                    return;
                }

            }
            else {
                //unknown command
                break;
            }

        }


        $this->writeLine("\"{$cmd}\" is not a valid command! Did you mean on of these? ", self::RED_TEXT);
        $this->write("", self::WHITE_TEXT);
        $this->printAvailableCommands($availableCommands, $parsedCmd);


    }


    private function printAvailableCommands(array $availableCommands, $prefix = "") {
        $this->writeLine("", self::RED_BG);

        $printCommand = function($array, $printFunction, $cmd = "", $nestingLevel = 0) {
            foreach($array as $key => $item) {

                if($nestingLevel == 0) {
                    $this->writeLine("");
                }

                if($cmd == "")  {
                    $command = $key;
                }
                else if(substr($cmd, "-1","1") == ":") {
                    $command = $cmd . $key;
                    $nestingLevel++;
                }
                else {
                    $command = $cmd . ":" . $key;
                }


                if(is_array($item)) {
                    $printFunction($item, $printFunction, $command, $nestingLevel+1);
                }
                else {
                    $this->writeLine(self::TAB . $command);
                }

            }
        };

        $printCommand($availableCommands, $printCommand, $prefix);

        $this->writeLine("", self::BLACK_BG);
    }

    private function findCommandFiles($dir) {

        $filesInDir = scandir($dir);

        $commands = [];

        foreach($filesInDir as $fileInDir) {

            if(in_array($fileInDir, self::IGNORED_DIRS)) {
                continue;
            }

            $path = $dir."\\".$fileInDir;

            if(is_dir($path)) {
                $commands[$fileInDir] = $this->findCommandFiles($path);
            }
            else if(is_file($path)) {
                $fileInDir = str_replace(".php", "", $fileInDir);

                $commands[$fileInDir] = $path;
            }
        }

        return $commands;
    }



    public function write($text, $option = "") {
        echo $option . $text;
    }

    public function writeLine($text, $option = "") {
        $this->write($text . PHP_EOL, $option);
    }


}
