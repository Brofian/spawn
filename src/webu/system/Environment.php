<?php

namespace webu\system;

use webu\system\Core\Custom\Logger;
use webu\system\core\Request;
use webu\system\core\Response;

/*
 * The Main Environment to handle the system
 */

class Environment
{

    /** @var Request */
    public $request;
    /** @var Response */
    public $response;

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();


        try {
            $this->init();
        } catch (\Throwable $exception) {
            $this->handleException($exception);
        }

    }


    public function init()
    {
        $this->request->gatherInformations();
        $this->request->addToAccessLog();


        $this->request->loadController(
            $this->request->getRequestController(),
            $this->request->getRequestActionPath(),
            $this
        );

        $this->response->getTwigHelper()->finish();
    }


    private function handleException(\Throwable $e)
    {

        Logger::writeToErrorLog($e->getTraceAsString(), $e->getMessage());

        if (MODE == 'dev') {
            $message = $e->getMessage() ?? 'No error-message provided!';
            $trace = $e->getTrace() ?? [];

            echo "ERROR: <b>" . $message . "</b><br><pre>";

            echo "<ul>";
            foreach ($trace as $step) {
                echo "<li>";
                echo "<b>" . ($step['file'] ?? "unknown") . ":" . ($step['line'] ?? "unknown") . "</b>";
                echo " in function <b>" . $step['function'] . "</b>";
            }
            echo "</ul>";

            var_dump($e);
        } else {
            echo "Leider ist etwas schief gelaufen :(";
        }
    }


}