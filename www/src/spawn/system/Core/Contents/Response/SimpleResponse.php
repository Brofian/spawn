<?php

namespace spawn\system\Core\Contents\Response;

class SimpleResponse extends AbstractResponse {

    public function __construct(string $responseText)
    {
        parent::__construct($responseText);
    }

}