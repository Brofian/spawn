<?php declare(strict_types=1);

namespace webu\system\Core\Helper;

use webu\system\Core\Request;
use webu\system\Core\Response;
use webu\system\Core\Services\ServiceContainerProvider;
use webu\system\Throwables\HeadersSendByException;

class HeaderHelper {

    public const RC_REDIRECT_TEMPORARILY = 307;
    public const RC_REDIRECT_SESSION = 302;
    public const RC_REDIRECT_FINAL = 301;

    private bool $headersSendBy = false;


    public function __construct()
    {

    }

    /**
     * @param string $targetId
     * @param array $parameters
     * @param int $responseCode
     * @param bool $replaceExisting
     * @throws HeadersSendByException
     */
    public function redirect(string $targetId, array $parameters = [], int $responseCode = self::RC_REDIRECT_TEMPORARILY, bool $replaceExisting = false) {
        $routingHelper = ServiceContainerProvider::getServiceContainer()->getServiceInstance('system.routing.helper');
        $location = $routingHelper->getLinkFromId($targetId, $parameters);
        $this->setHeader("Location: " . $location, $responseCode, $replaceExisting);
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