<?php

namespace Core\ViewModel\Sidebar;

class GeneralSidebarLink
{
    private $htmlId;
    private $text;
    private $url;
    private $precedingText;

    /**
     * GeneralSidebarLink constructor.
     *
     * @param $htmlId
     * @param $text
     * @param $url
     * @param string $modifier
     * @param string $precedingText
     */
    public function __construct($htmlId, $text, $url, $modifier = '', $precedingText = '')
    {
        $this->htmlId = $htmlId;
        $this->text = $text;
        $this->url = $url;
        $this->modifier = $modifier;
        $this->precedingText = $precedingText;
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

    /**
     * @return string CSS class modifier
     */
    public function getModifier()
    {
        return $this->modifier;
    }

    /**
     * @return string text to insert on same line before link
     */
    public function getPrecedingText()
    {
        return $this->precedingText;
    }

    /**
     * @param string $precedingText text to insert on same line before link
     */
    public function setPrecedingText($precedingText)
    {
        $this->precedingText = $precedingText;
    }
}
