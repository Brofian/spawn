<?php

namespace webu\system\Core\Extensions\Twig;


use webu\system\Core\Base\Extensions\Twig\FilterExtension;

class HashFilterExtension extends FilterExtension
{

    protected function getFilterName(): string
    {
        return "hash";
    }

    protected function getFilterFunction(): callable
    {
        return function($string, $hashtype = "md5") {
            return hash($hashtype, $string);
        };
    }

    protected function getFilterOptions(): array
    {
        return [
            'is_safe' => ['html']
        ];
    }
}