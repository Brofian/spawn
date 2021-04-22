<?php

namespace webu\system;

use webu\system\Core\Contents\Modules\ModuleLoader;
use webu\system\Core\Custom\Logger;
use webu\system\Core\Helper\RoutingHelper2;
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


    /**
     * Environment constructor.
     */
    public function __construct()
    {
        $this->request = new Request($this);
        $this->response = new Response($this);


        try {
            $this->init();
        } catch (\Throwable $exception) {
            $this->handleException($exception);
        }

    }


    public function init()
    {
        $this->request->gatherInformationsFromRequest();
        $this->request->addToAccessLog();

        $this->request->loadController();

        $this->response->prepare();

        $this->response->finish($this->request->getModuleCollection(), $this->request->getContext());
    }

    /**
     * @param \Throwable $e
     */
    private function handleException(\Throwable $exception)
    {

        Logger::writeToErrorLog($exception->getTraceAsString(), $exception->getMessage());

        if (MODE == 'dev') {
            $message = $exception->getMessage() ?? 'No error-message provided!';
            $trace = $exception->getTrace() ?? [];

            echo "ERROR: <b>" . $message . "</b><br><pre>";

            echo "<ul>";
            foreach ($trace as $step) {
                echo "<li>";
                echo "<b>" . ($step['file'] ?? "unknown") . ":" . ($step['line'] ?? "unknown") . "</b>";
                echo " in function <b>" . $step['function'] . "</b>";
            }
            echo "</ul>";

            var_dump($exception);
        } else {
            echo "Leider ist etwas schief gelaufen :(";
        }

    }


    /**
     * @return string
     */
    public function finish() {
        return $this->response->getHtml();
    }
}