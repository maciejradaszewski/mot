<?php

namespace Core\Action;

class RedirectToUrl extends AbstractRedirectActionResult
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }
}
