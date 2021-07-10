<?php declare(strict_types=1);

namespace webu\system\Core;

/*
 *  The default Class to store all Request Information
 */

use webu\system\Core\Base\Helper\DatabaseHelper;
use webu\system\Core\Contents\Collection\AssociativeCollection;
use webu\system\Core\Contents\ValueBag;
use webu\system\Core\Custom\Logger;
use webu\system\Core\Helper\RoutingHelper;
use webu\system\Core\Services\ServiceContainerProvider;
use webuApp\Models\RewriteUrl;

class Request
{
    protected AssociativeCollection $get;
    protected AssociativeCollection $post;
    protected AssociativeCollection $cookies;
    protected ValueBag $curl_values;

    protected string $requestURI;
    protected string $requestHostName;
    protected string $requestPath;
    protected string $requestMethod;
    protected bool $isHttps;


    public function __construct()
    {
        $this->curl_values = new ValueBag();

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
        /** @var RoutingHelper $routingHelper */
        $routingHelper = ServiceContainerProvider::getServiceContainer()->getServiceInstance('system.routing.helper');
        /** @var DatabaseHelper $databaseHelper */
        $databaseHelper = ServiceContainerProvider::getServiceContainer()->getServiceInstance('system.database.helper');

        $newURL = $routingHelper->rewriteURL(
            $this->requestPath,
            RewriteUrl::loadAll($databaseHelper),
            $this->curl_values
        );

        $parts = parse_url($newURL);
        parse_str($parts['query'], $query);

        foreach($query as $key => $value) {
            $this->get->set($key, $value);
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

    public function getCurlValues(): ValueBag {
        return $this->curl_values;
    }

    public function getRequestURI(): string {
        return $this->requestURI;
    }





}
