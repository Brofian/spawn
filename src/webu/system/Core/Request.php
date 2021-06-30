<?php declare(strict_types=1);

namespace webu\system\Core;

/*
 *  The default Class to store all Request Information
 */

use webu\system\Core\Base\Controller\BaseController;
use webu\system\Core\Base\Controller\ControllerInterface;
use webu\system\Core\Base\Helper\DatabaseHelper;
use webu\system\Core\Contents\Collection\AssociativeCollection;
use webu\system\Core\Contents\Collection\Collection;
use webu\system\Core\Contents\ContentLoader;
use webu\system\Core\Contents\Context;
use webu\system\Core\Contents\Modules\Module;
use webu\system\Core\Contents\Modules\ModuleCollection;
use webu\system\Core\Contents\Modules\ModuleController;
use webu\system\Core\Contents\Modules\ModuleLoader;
use webu\system\Core\Contents\Modules\ModuleNamespacer;
use webu\system\Core\Contents\ValueBag;
use webu\system\Core\Custom\Logger;
use webu\system\Core\Helper\CookieHelper;
use webu\system\Core\Helper\FrameworkHelper\CUriConverter;
use webu\system\Core\Helper\RoutingHelper;
use webu\system\Core\Helper\SessionHelper;
use webu\system\Core\Helper\URIHelper;
use webu\system\Core\Helper\XMLReader;
use webu\system\Core\Services\Service;
use webu\system\Core\Services\ServiceContainer;
use webu\system\Core\Services\ServiceLoader;
use webu\system\Core\Services\ServiceTags;
use webu\system\Environment;
use webu\system\Throwables\ModulesNotLoadedException;
use webu\system\Throwables\NoModuleFoundException;
use webuApp\Models\RewriteUrl;

class Request
{

    protected AssociativeCollection $get;
    protected AssociativeCollection $post;
    protected AssociativeCollection $cookies;

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



    public function writeAccessLogEntry()
    {
        Logger::writeToAccessLog("Call to \"{$this->requestURI}\"");
    }


    /*
    public function checkRewriteURL() {

        $newURL = $this->getRoutingHelper()->rewriteURL(
            $this->requestURI,
            RewriteUrl::loadAll($this->getDatabaseHelper()),
            $this->curlValueBag
        );


        $this->requestURI;

        $parts = parse_url($newURL);
        parse_str($parts['query'], $query);

        foreach($query as $key => $value) {
            $this->get[$key] = $value;
        }
    }


    */
}