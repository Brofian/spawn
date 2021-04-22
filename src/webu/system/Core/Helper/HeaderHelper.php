<?php

namespace webu\system\Core\Helper;

use webu\system\core\Request;
use webu\system\core\Response;
use webu\system\Throwables\HeadersSendByException;

class HeaderHelper {

    /** @var int  */
    public const RC_REDIRECT_TEMPORARILY = 307;
    public const RC_REDIRECT_SESSION = 302;
    public const RC_REDIRECT_FINAL = 301;


    /** @var bool  */
    private $headersSendBy = false;

    /** @var $request Request */
    private $request;

    /** @var $response Response */
    private $response;

    /**
     * HeaderHelper constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @param string $targetId
     * @param array $parameters
     * @param int $responseCode
     * @param bool $replaceExisting
     * @throws HeadersSendByException
     */
    public function redirect(string $targetId, array $parameters = [], int $responseCode = self::RC_REDIRECT_TEMPORARILY, bool $replaceExisting = false) {
        $location = $this->request->getRoutingHelper()->getLinkFromId($targetId, $parameters);
        $this->setHeader("Location: " . $location, $replaceExisting, $responseCode);
    }


    /**
     * @param string $header
     * @param int $responseCode
     * @param bool $replaceExisting
     * @throws HeadersSendByException
     */
    public function setHeader(string $header, int $responseCode = 200, bool $replaceExisting = false) {
        if($this->headersSendBy) {
            throw new HeadersSendByException();
        }

        header($header, $replaceExisting, $responseCode);
    }


    public function setHeadersSentBy() {
        $this->headersSendBy = true;
    }

}