<?php

namespace webu\system\Core\Extensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LinkExtension extends AbstractExtension
{

    /** @var string  */
    private $fullurl = '';


    public function __construct(string $fullUrl)
    {
        $this->fullurl = $fullUrl;
    }


    public function getFunctions()
    {
        return [
            new TwigFunction('link', [$this, 'formatRelativeLink']),
        ];
    }


    public function formatRelativeLink(string $relativeLink)
    {

        if($relativeLink[0] != '/') {
            $relativeLink = '/' . $relativeLink;
        }

        return $this->fullurl . $relativeLink;
    }

}