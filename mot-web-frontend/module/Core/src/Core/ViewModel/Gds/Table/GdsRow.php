<?php

namespace Core\ViewModel\Gds\Table;

class GdsRow implements GdsRowFlowInterface
{
    private $htmlId = null;
    /** @var GdsTableActionLink */
    private $actionLink = null;

    /** @var GdsRowLabel */
    private $label = null;

    /** @var GdsRowValue */
    private $value = null;

    /** @var GdsRowValueMetaData */
    private $valueMetaData = null;

    private $parentTable;

    public function __construct(GdsTable $parentTable, $htmlId)
    {
        $this->parentTable = $parentTable;
        $this->htmlId = $htmlId;
    }

    public function getHtmlId()
    {
        return $this->htmlId;
    }

    public function setLabel($content, $escape = true)
    {
        $this->label = new GdsRowLabel($this, $content, $escape);
        return $this->label;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setActionLink($text, $url, $tooltip = ' ')
    {
        $this->actionLink = new GdsTableActionLink($this, $text, $url, $tooltip);

        return $this->actionLink;
    }

    public function hasActionLink()
    {
        return $this->actionLink !== null;
    }

    public function getActionLink()
    {
        return $this->actionLink;
    }

    public function newRow($htmlId = null)
    {
        return $this->parentTable->newRow($htmlId = null);
    }

    public function getRow($index)
    {
        return $this->parentTable->getRow($index);
    }

    public function getRows()
    {
        return $this->parentTable->getRows();
    }

    public function setValue($content, $escape = true)
    {
        $this->value = new GdsRowValue($this, $content, $escape);
        return $this->value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValueMetaData($content, $escape = true)
    {
        $this->valueMetaData = new GdsRowValueMetaData($this, $content, $escape);
        return $this->valueMetaData;
    }

    public function getValueMetaData()
    {
        return $this->valueMetaData;
    }
}
