<?php

namespace Core\ViewModel\Gds\Table;

class GdsTableActionLink extends AbstractGdsRowElement
{
    private $url;
    private $tooltip;
    private $text;

    public function __construct(GdsRow $parentRow, $text, $url, $tooltip)
    {
        parent::__construct($parentRow);

        $this->url = $url;
        $this->tooltip = $tooltip;
        $this->text = $text;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getTooltip()
    {
        return $this->tooltip;
    }

    public function getText()
    {
        return $this->text;
    }
}
