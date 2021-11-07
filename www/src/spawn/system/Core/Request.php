<?php declare(strict_types=1);

namespace spawn\system\Core;

/*
 *  The default Class to store all Request Information
 */

use spawn\system\Core\Contents\Collection\AssociativeCollection;
use spawn\system\Core\Custom\Logger;
use spawn\system\Core\Helper\RoutingHelper;
use spawn\system\Core\Services\ServiceContainerProvider;
use spawnApp\Models\RewriteUrl;

class Request
{
    protected AssociativeCollection $get;
    protected AssociativeCollection $post;
    protected AssociativeCollection $cookies;
    protected array $curl_values = [];

    protected string $requestURI;
    protected string $requestHostName;
    protected string $requestPath;
    protected string $requestMethod;
    protected bool $isHttps;


    public function __construct()
    {
        $this->enrichGetValueBag();
        $this->enrichPostValueBag();
        $this->enrichCookieValueBag();

        $this->enrichIsHttps();
        $this->enrichRequestHostName();
        $this->enrichRequestPath();
        $this->enrichRequestURI();
        $this->enrichRequestMethod();

        $this->checkForRewriteUrl();

        if(MODE == 'dev') {
            $this->writeAccessLogEntry();
        }
    }

    protected function enrichGetValueBag(): void {
        $this->get = new AssociativeCollection();
        foreach($_GET as $key => $value) {
            $this->get->set($key, $value);
        }
    }

    protected function enrichPostValueBag(): void {
        $this->post = new AssociativeCollection();
        foreach($_POST as $key => $value) {
            $this->post->set($key, $value);
        }
    }

    protected function enrichCookieValueBag(): void {
        $this->cookies = new AssociativeCollection();
        foreach($_COOKIE as $key => $value) {
            $this->cookies->set($key, $value);
        }
    }

    protected function enrichRequestPath(): void {
        $this->requestPath = $_SERVER['REQUEST_URI'] ?? '/';
    }

    protected function enrichRequestHostName(): void {
        $this->requestHostName = $_SERVER['HTTP_HOST'] ?? "";
    }

    protected function enrichRequestUri() {
        $https = $this->isHttps ? 'https' : 'http';
        $hostname = $this->requestHostName;
        $path = $this->requestPath;

        $this->requestURI = "{$https}://{$hostname}{$path}";
    }

    protected function enrichIsHttps(): void {
        $serverHttps = $_SERVER['HTTPS'] ?? '';
        $this->isHttps = ('on' == $serverHttps);
    }

    protected function enrichRequestMethod() {
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
    }

    protected function checkForRewriteUrl() {

        if($this->get->get('controller') != null && $this->get->get('action') != null) {
            return;
        }

        /** @var RoutingHelper $routingHelper */
        $routingHelper = ServiceContainerProvider::getServiceContainer()->getServiceInstance('system.routing.helper');

        $newURL = $routingHelper->rewriteURL(
            $this->requestPath,
            $this->curl_values
        );

        $parts = parse_url($newURL);
        if(isset($parts['query'])) {
            //read and add get parameters
            parse_str($parts['query'], $query);

            foreach($query as $key => $value) {
                $this->get->set($key, $value);
            }
        }
    }


    public function writeAccessLogEntry()
    {
        Logger::writeToAccessLog("Call to \"{$this->requestURI}\"");
    }

    public function getGet(): AssociativeCollection {
        return $this->get;
    }

    public function getCookies(): AssociativeCollection {
        return $this->cookies;
    }

    public function getPost(): AssociativeCollection {
        return $this->post;
    }

    public function getCurlValues(): array {
        return $this->curl_values;
    }

    public function getRequestURI(): string {
        return $this->requestURI;
    }





}
