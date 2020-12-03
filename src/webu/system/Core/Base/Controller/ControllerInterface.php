<?php

namespace webu\system\Core\Base\Controller;

use webu\system\core\Request;
use webu\system\core\Response;

Interface ControllerInterface {

    public function onControllerStart(Request $request, Response $response);

    public function onControllerStop(Request $request, Response $response);



}