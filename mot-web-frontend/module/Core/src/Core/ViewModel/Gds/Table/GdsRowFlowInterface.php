<?php

namespace Core\ViewModel\Gds\Table;

interface GdsRowFlowInterface extends GdsTableFlowInterface
{
    public function getHtmlId();

    /**
     * @param $content
     * @param bool $escape
     * @return GdsRowLabel
     */
    public function setLabel($content, $escape = true);

    /**
     * @return GdsRowLabel
     */
    public function getLabel();

    /**
     * @param $content
     * @param bool $escape
     * @return GdsRowValue
     */
    public function setValue($content, $escape = true);

    /**
     * @return GdsRowValue
     */
    public function getValue();

    /**
     * @param $text
     * @param $url
     * @param string $tooltip
     * @return GdsTableActionLink
     */
    public function setActionLink($text, $url, $tooltip = ' ');

    /**
     * @return GdsTableActionLink
     */
    public function getActionLink();

    /**
     * @return bool
     */
    public function hasActionLink();
}
