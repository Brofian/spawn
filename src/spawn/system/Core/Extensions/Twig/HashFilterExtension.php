<?php

namespace spawn\system\Core\Extensions\Twig;


use spawn\system\Core\Base\Extensions\Twig\FilterExtension;

class HashFilterExtension extends FilterExtension
{

    /**
     * @return string
     */
    protected function getFilterName(): string
    {
        return "hash";
    }

    /**
     * @return callable
     */
    protected function getFilterFunction(): callable
    {
        return function($string, $hashtype = "md5") {
            return hash($hashtype, $string);
        };
    }

    /**
     * @return array
     */
    protected function getFilterOptions(): array
    {
        return [
            'is_safe' => ['html']
        ];
    }
}