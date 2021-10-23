<?php

namespace spawn\system\Core\Contents\Response;

use spawn\system\Core\Contents\Response\Exceptions\JsonConvertionException;

class JsonResponse extends AbstractResponse {

    public function __construct(array $responseArray)
    {
        try {
            $jsonResponse = json_encode($responseArray);
        }
        catch (\Exception $e) {
            $jsonResponse = (string)(new JsonConvertionException($responseArray));
        }

        parent::__construct($jsonResponse);
    }

}