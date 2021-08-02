<?php

namespace spawn\system\Core\Helper;


use spawn\system\Core\Contents\ValueBag;
use spawn\system\Core\Helper\FrameworkHelper\CUriConverter;
use spawn\system\Core\Services\Service;
use spawn\system\Core\Services\ServiceContainer;
use spawn\system\Core\Services\ServiceContainerProvider;
use spawnApp\Database\SeoUrlTable\SeoUrlEntity;
use spawnApp\Database\SeoUrlTable\SeoUrlRepository;
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

        if(!preg_match('/^.*Action$/m', $action)) {
            $actionStr =  $action."Action";
        }
        else {
            $actionStr = $action;
        }

        if(!method_exists($controllerCls->getClass(), $actionStr)) {
            //action does not exist
            $controllerCls = $this->serviceContainer->getService(self::FALLBACK_SERVICE);
            $actionStr = self::FALLBACK_ACTION;
            return;
        }

        return;
    }



    public function rewriteURL(string $original, ValueBag &$values): string {

        $original = trim($original, '/? #');
        if($original == '' || strlen($original)) {
            $original = '/' . $original;
        }
        //$original = "/[whatever]"

        /** @var SeoUrlRepository $seoUrlRepository */
        $seoUrlRepository = $this->serviceContainer->getServiceInstance('system.repository.seo_urls');
        $rewrite_urls = $seoUrlRepository->search();



        /** @var SeoUrlEntity $seo_url */
        foreach($rewrite_urls as $seo_url) {
            $regex = CUriConverter::cUriToRegex($seo_url->getCUrl());

            $matches = [];
            $hasMatched = preg_match_all($regex, $original, $matches);

            if($hasMatched) {

                $parameterNameList = CUriConverter::getParameterNames($seo_url->getCUrl());

                for($i = 1; $i < count($matches); $i++) {
                    $values->set(
                        $parameterNameList[$i-1],
                        $matches[$i]
                    );
                }

                return self::getFormattedLink($seo_url->getController(), $seo_url->getAction());
            }
        }

        return self::getFormattedLink('system.fallback.404', 'error404');
    }

    public function getSeoLinkByParameters(string $controller, string $action, array $parameters = []): string {


        /** @var SeoUrlRepository $seoUrlRepository */
        $seoUrlRepository = $this->serviceContainer->getServiceInstance('system.repository.seo_urls');
        $seoUrlCollection = $seoUrlRepository->search([
            'controller' => $controller,
            'action' => $action
        ]);

        $seoUrl = $seoUrlCollection->first();

        if($seoUrl instanceof SeoUrlEntity) {

            // TODO::implement $parameters in cUrl
            return $seoUrl->getCUrl();
        }
        else {
            return self::getFormattedLink(self::FALLBACK_SERVICE, self::FALLBACK_ACTION);
        }
    }


    public static function getFormattedLink(string $controller, string $action): string {
        return "/?controller=$controller&action=$action";
    }


}