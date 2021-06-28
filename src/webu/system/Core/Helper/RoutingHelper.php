<?php

namespace webu\system\Core\Helper;



use webu\system\Core\Contents\Modules\Module;
use webu\system\Core\Contents\Modules\ModuleCollection;
use webu\system\Core\Contents\Modules\ModuleController;
use webu\system\Core\Contents\ValueBag;
use webu\system\Core\Helper\FrameworkHelper\CUriConverter;
use webu\system\Core\Services\Service;
use webu\system\Core\Services\ServiceContainer;
use webuApp\Models\RewriteUrl;

class RoutingHelper
{

    protected ServiceContainer $serviceContainer;


    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }


    public function route(string $controller, string $action, ?Service &$controllerCls, ?string &$actionStr): void {

        if($controller == "" || $action == "") {
            $controllerCls = $this->serviceContainer->getService('system.fallback.404');
            $actionStr = 'error404Action';
            return;
        }


        //find service
        $controllerCls = $this->serviceContainer->getService($controller);
        if(!$controllerCls) {
            //controller does not exist
            $controllerCls = $this->serviceContainer->getService('system.fallback.404');
            $actionStr = 'error404Action';
            return;
        }

        $actionStr =  $action."Action";
        if(!method_exists($controllerCls->getClass(), $actionStr)) {
            //action does not exist
            $controllerCls = $this->serviceContainer->getService('system.fallback.404');
            $actionStr = 'error404Action';
            return;
        }


        return;
    }



    public function rewriteURL(string $original, array $rewrite_urls, ValueBag &$values): string {

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

        return $original;
    }


}