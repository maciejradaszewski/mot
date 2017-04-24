<?php

namespace Dashboard\ViewModel;

class LinkViewModel
{
    /**
     * @var string $text
     */
    private $text;

    /**
     * @var string $href
     */
    private $href;

    /**
     * LinkViewModel constructor.
     *
     * @param string $text
     * @param string $href
     */
    public function __construct($text, $href)
    {
        $this->text = $text;
        $this->href = $href;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }
}
