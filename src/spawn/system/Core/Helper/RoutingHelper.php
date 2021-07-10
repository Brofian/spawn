<?php

namespace spawn\system\Core\Helper;


use spawn\system\Core\Contents\ValueBag;
use spawn\system\Core\Helper\FrameworkHelper\CUriConverter;
use spawn\system\Core\Services\Service;
use spawn\system\Core\Services\ServiceContainer;
use spawn\system\Core\Services\ServiceContainerProvider;
use spawnApp\Models\RewriteUrl;

class RoutingHelper
{
    const FALLBACK_SERVICE = 'system.fallback.404';
    const FALLBACK_ACTION = 'error404Action';


    protected ServiceContainer $serviceContainer;


    public function __construct()
    {
        $this->serviceContainer = ServiceContainerProvider::getServiceContainer();
    }


    public function route(string $controller, string $action, ?Service &$controllerCls, ?string &$actionStr): void {


        if($controller == "" || $action == "") {
            $controllerCls = $this->serviceContainer->getService(self::FALLBACK_SERVICE);
            $actionStr = self::FALLBACK_ACTION;
            return;
        }


        //find service
        $controllerCls = $this->serviceContainer->getService($controller);
        if(!$controllerCls) {
            //controller does not exist
            $controllerCls = $this->serviceContainer->getService(self::FALLBACK_SERVICE);
            $actionStr = self::FALLBACK_ACTION;
            return;
        }

        $actionStr =  $action."Action";
        if(!method_exists($controllerCls->getClass(), $actionStr)) {
            //action does not exist
            $controllerCls = $this->serviceContainer->getService(self::FALLBACK_SERVICE);
            $actionStr = self::FALLBACK_ACTION;
            return;
        }


        return;
    }



    public function rewriteURL(string $original, array $rewrite_urls, ValueBag &$values): string {

        $original = trim($original, '/? ');

        if($original == '' || strlen($original)) {
            $original = '/' . $original;
        }

        /** @var RewriteUrl $rewrite_url */
        foreach($rewrite_urls as $rewrite_url) {
            $regex = CUriConverter::cUriToRegex($rewrite_url->getCUrl());

            $matches = [];
            $hasMatched = preg_match_all($regex, $original, $matches);

            if($hasMatched) {

                $parameterNameList = CUriConverter::getParameterNames($rewrite_url->getCUrl());

                for($i = 1; $i < count($matches); $i++) {
                    $values->set(
                        $parameterNameList[$i-1],
                        $matches[$i]
                    );
                }

                return $rewrite_url->getRewriteUrl();
            }
        }

        $fallbackPath = "/?controller=system.fallback.404&action=error404";
        return $fallbackPath;
    }


}