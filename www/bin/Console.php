<?php declare(strict_types=1);


namespace spawn\bin;

use bin\spawn\IO;
use spawn\system\Core\Custom\AbstractCommand;
use spawn\system\Core\Services\Service;
use spawn\system\Core\Services\ServiceContainerProvider;
use spawn\system\Core\Services\ServiceTags;

class Console {

    /** @var mixed|string  */
    private $command = "";
    /** @var array  */
    private $params = [];

    public function __construct($arguments)
    {
        // remove "bin/console" -> can normally be ignored
        array_shift($arguments);
        //the command, e.g. "debug:test"
        $this->command = array_shift($arguments) ?? '';

        // check special parameters
        if(in_array('-v',$arguments)) IO::$verboseLevel = 1;
        else if(in_array('-vv',$arguments)) IO::$verboseLevel = 2;
        else if(in_array('-vvv',$arguments)) IO::$verboseLevel = 3;
        $arguments = array_diff( $arguments, ['-v', '-vv', '-vvv'] );

        $this->params = $this->interpretParameters($arguments);

        $matchingCommands = $this->findMatchingCommands();

        $perfectMatches = count($matchingCommands['perfectMatches']);
        if($perfectMatches === 1) {

            if(in_array('--help', $arguments) || in_array('-h', $arguments)) {
                $this->printCommandHelp($matchingCommands['perfectMatches'][0]);
                return;
            }

            $this->executeCommand($matchingCommands['perfectMatches'][0]);
            return;
        }
        elseif($perfectMatches > 1) {
            $matchingCommands['matches'] = $matchingCommands['perfectMatches'];
        }

        IO::printError('No matching command found!');
        IO::reset();

        if(count($matchingCommands['matches'])) {
            $this->listPossibleCommands($matchingCommands['matches']);
        }

    }

    protected function interpretParameters(array $parameters): array {
        $parameterList = [];
        $standaloneParameterIndex = 0;

        foreach($parameters as $param) {

            if(ltrim($param, '-') === '') {
                continue;
            }

            if(substr($param, 0, 2) == '--') {
                // e.g. "--test" or "--test=Helloworld"
                $param = ltrim($param, '-');
                if(strpos($param, '=') !== false) {
                    list($param, $value) = explode('=', $param, 2);
                    $parameterList[$param] = $value;
                }
                else {
                    $parameterList[$param] = true;
                }
            }
            elseif (substr($param, 0, 1) == '-') {
                // e.g. "-e" or "-eHelloworld"
                $param = ltrim($param, '-');
                if(strlen($param) > 1) {
                    $parameterList[$param[0]] = substr($param, 1);
                }
                else {
                    $parameterList[$param] = true;
                }
            }
            else {
                $parameterList[$standaloneParameterIndex] = $param;
                $standaloneParameterIndex++;
            }

        }

        return $parameterList;
    }


    /**
     * @return Service[]
     */
    protected function findMatchingCommands(): array {
        $commandServices = ServiceContainerProvider::getServiceContainer()->getServicesByTag(ServiceTags::CONSOLE_COMMAND);
        $commandPieces = explode(':', $this->command);
        $commandPiecesLength = count($commandPieces);

        $matchingCommands = [
            'bestMatch' => 0,
            'matches' => [],
            'perfectMatches' => []
        ];


        foreach($commandServices as $serviceId => $commandService) {
            /** @var AbstractCommand $class */
            $class = $commandService->getClass();

            $matchingLevel = $this->matchCommandWithPattern($class::getCommand(), explode(':', $this->command), $commandPiecesLength);
            if($matchingLevel == $commandPiecesLength) {
                $matchingCommands['perfectMatches'][] = $commandService;
            }
            elseif ($matchingLevel == $matchingCommands['bestMatch']) {
                $matchingCommands['matches'][] = $commandService;
            }
            elseif($matchingLevel > $matchingCommands['bestMatch']) {
                $matchingCommands['bestMatch'] = $matchingLevel;
                $matchingCommands['matches'] = [$commandService];
            }
        }

        return $matchingCommands;
    }

    protected function matchCommandWithPattern(string $pattern, array $commandPieces, int $commandPiecesLength): int {
        $patternPieces = explode(':', $pattern);
        $matchingParts = 0;


        foreach($patternPieces as $pos => $patternPiece) {
            if($pos < $commandPiecesLength && strpos($patternPiece, $commandPieces[$pos]) === 0) {
                $matchingParts++;
            }
            else {
                break;
            }
        }

        return $matchingParts;
    }


    protected function executeCommand(Service $commandService): void {
        /** @var AbstractCommand $instance */
        $instance = $commandService->getInstance();
        $parameters = $instance::createParameterArray($this->params);

        try {
            $result = $instance->execute($parameters);
        }
        catch (\Exception $e) {
            IO::printError('ERROR ' . $e->getCode() .' => '. $e->getMessage());
            IO::printError($e->getFile() .':'.$e->getLine());
            IO::printError(implode(PHP_EOL, $e->getTrace()));
            $result = $e->getCode();
        }


        IO::endLine();
        if($result) {
            IO::printError('An error occurred! There is probably more output above!');
        }
        else {
            IO::printSuccess('Command successfully executed!');
        }
        IO::reset();
    }

    /**
     * @param Service[] $possibleCommands
     */
    protected function listPossibleCommands(array $possibleCommands): void {

        $sortedCommands = [];
        foreach($possibleCommands as $commandService) {
            /** @var AbstractCommand $class */
            $class = $commandService->getClass();
            $namespace = explode(':', $class::getCommand())[0];
            $sortedCommands[$namespace][] = $commandService;
        }

        $segmentLengths = [];
        $lines = [];
        /** @var string $namespace      @var array $commandList    */
        foreach($sortedCommands as $namespace => $commandList) {
            $lines[] = ["[$namespace]", '', ''];

            foreach($commandList as $commandService) {
                /** @var AbstractCommand $class */
                $class = $commandService->getClass();
                $command = $class::getCommand();
                $description = $class::getShortDescription();
                $line = ['   ', $command, '   ', $description];
                $lines[] = $line;

                foreach($line as $pos => $segment) {
                    $length = strlen($segment);
                    if(!isset($segmentLengths[$pos]) || $length > $segmentLengths[$pos]) {
                        $segmentLengths[$pos] = $length;
                    }
                }
            }

            $lines[] = ['', '', ''];
        }



        $totalLineLength = 0;
        foreach($segmentLengths as $segmentLength) {
            $totalLineLength += $segmentLength;
        }

        $emptyLine = str_pad('', $totalLineLength, ' ');


        IO::endLine();
        IO::printLine($emptyLine, IO::RED_BG);
        IO::printLine(str_pad('Did you mean any of these?', $totalLineLength, ' '));
        IO::printLine($emptyLine);
        foreach($lines as $line) {
            foreach($line as $pos => $segment) {
                IO::print(str_pad($segment, $segmentLengths[$pos], ' '));
            }
            IO::endLine();
        }
        IO::printLine($emptyLine);
        IO::reset();
        IO::endLine();
    }


    protected function printCommandHelp(Service $command): void {
        //TODO
        IO::printLine('TODO');
        return;
    }


}
