<?php declare(strict_types=1);

namespace spawn\system\Core\Extensions\Twig;


use spawn\system\Core\Base\Extensions\Twig\FilterExtension;

class PreviewFilterExtension extends FilterExtension
{

    /**
     * @return string
     */
    protected function getFilterName(): string
    {
        return "preview";
    }

    /**
     * @return callable
     */
    protected function getFilterFunction(): callable
    {
        return function($text, int $length) {

            $trimmedText = trim(substr($text, 0, $length));

            if(strlen($text) > $length) {
                $trimmedText .= "...";
            }

            return $trimmedText;
        };
    }

    /**
     * @return array
     */
    protected function getFilterOptions(): array
    {
        return [];
    }
}