<?php declare(strict_types=1);


namespace spawn\bin;

use bin\spawn\IO;
use spawn\system\Core\Helper\URIHelper;

class Console {

    const COMMAND_ROOT = ROOT . "\\dev\\console";
    const IGNORED_DIRS = [
        '.',
        '..',
        'callable'
    ];



    /** @var mixed|string  */
    private $command = "";
    /** @var array  */
    private $params = [];

    /** @var array  */
    private $commandList = [];

    public function __construct($arguments)
    {
        if(in_array('-v',$arguments)) IO::$verboseLevel = 1;
        else if(in_array('-vv',$arguments)) IO::$verboseLevel = 2;
        else if(in_array('-vvv',$arguments)) IO::$verboseLevel = 3;

        $this->command = array_shift($arguments);

        $this->params = $arguments;

        $this->commandList = $this->findCommandFiles(self::COMMAND_ROOT);
        $this->runCommand();
    }


    private function runCommand() {

        if(count($this->params) < 1) {
            IO::printLine("Please enter a command!");
            $this->printAvailableCommands($this->commandList);
            return;
        }
        else if($this->params[0] == "help" || $this->params[0] == "?") {
            $this->printAvailableCommands($this->commandList, "", "");
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


        IO::printLine("\"{$cmd}\" is not a valid command! Did you mean on of these? ", IO::RED_TEXT);
        IO::endLine(IO::WHITE_TEXT);
        $this->printAvailableCommands($availableCommands, $parsedCmd);

    }


    private function printAvailableCommands(array $availableCommands, $prefix = "", $cFlags = null) {
        $colorFlag = ($cFlags === null) ? IO::RED_BG : $cFlags;

        IO::endLine($colorFlag);

        $printCommand = function($array, $printFunction, $cmd = "", $nestingLevel = 0) {
            foreach($array as $key => $item) {

                if($nestingLevel == 0) {
                    IO::endLine();
                }

                if($cmd == "")  {
                    $command = $key;
                }
                else if(substr($cmd, -1,1) == ":") {
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
                    IO::printLine(IO::TAB . $command);
                }

            }
        };

        $printCommand($availableCommands, $printCommand, $prefix);

        IO::endLine(IO::BLACK_BG);
    }

    private function findCommandFiles($dir) {

        URIHelper::pathifie($dir, DIRECTORY_SEPARATOR);

        $filesInDir = scandir($dir );

        $commands = [];

        foreach($filesInDir as $fileInDir) {

            if(in_array($fileInDir, self::IGNORED_DIRS)) {
                continue;
            }

            $path = $dir."/".$fileInDir;

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




}
