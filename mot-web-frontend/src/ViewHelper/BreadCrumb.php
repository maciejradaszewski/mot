<?php
namespace Dvsa\Mot\Frontend\ViewHelper;

use Zend\View\Helper\AbstractHelper;

/**
 * Breadcrumb view helper
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
class BreadCrumb extends AbstractHelper
{
    public function __invoke($breadcrumbs)
    {
        $list = array();

        foreach ($breadcrumbs as $breadcrumb => $class) {
            $list[] = '<li '. ($class ? 'class="' . $class . '"' : '') . '><i class="fa fa-arrow-circle-right"></i> ' . $this->view->escapeHtml($this->view->translate($breadcrumb)) . '</li>';
        }

        return implode($list);
    }
}
