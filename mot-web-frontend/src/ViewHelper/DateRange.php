<?php
namespace Dvsa\Mot\Frontend\ViewHelper;

use Zend\View\Helper\AbstractHelper;

/**
 * class DateRange
 */
class DateRange extends AbstractHelper
{
    public function __invoke($rangeKey, $rangeValue, $currentRange, $currentParams)
    {
        $escapeRangeValue = $this->view->escapeHtml($rangeValue);
        $escapeRangeKey = $this->view->escapeHtml($rangeKey);

        if ($rangeKey == $currentRange) {
            return '<li><strong>' . $escapeRangeValue . '</strong></li>';
        }

        $currentParams['dateRange'] = $rangeKey;
        return '<li><a id="' . $escapeRangeKey . 'range" href="' . $this->view->url(null, array(), array('query' => $currentParams), true) . ' ">' . $escapeRangeValue . '</a></li>';
    }
}
