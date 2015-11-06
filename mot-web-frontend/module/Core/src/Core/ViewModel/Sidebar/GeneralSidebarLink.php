<?php

namespace Core\ViewModel\Sidebar;

class GeneralSidebarLink
{
    private $htmlId;
    private $text;
    private $url;

    function __construct($htmlId, $text, $url)
    {
        $this->htmlId = $htmlId;
        $this->text = $text;
        $this->url = $url;
    }

    public function getHtmlId()
    {
        return $this->htmlId;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getUrl()
    {
        return $this->url;
    }
}
