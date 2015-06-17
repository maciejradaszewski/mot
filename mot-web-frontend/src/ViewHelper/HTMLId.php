<?php
namespace Dvsa\Mot\Frontend\ViewHelper;

use Zend\View\Helper\AbstractHelper;

/**
 * Class HTMLId
 *
 * This provides a consistent way to ensure that a valid HTML identifier or
 * name is produced.
 *
 * @package Dvsa\Mot\Frontend\ViewHelper
 */
class HTMLId extends AbstractHelper
{
    /**
     * This is a variadic function that will return a string containing the
     * input string but having converted all '.'(dot) characters to '-'(hyphers).
     *
     * If only a single string is passed, it will be returned after the
     * above conversion has been performed. If more than a single parameter
     * was passed, the first is assumed to be a printf() like format string
     * and the remaining arguments will be passed through to vsprintf().
     *
     * @return String containing valid HTML identifier (HTML escaped!)
     */
    public function __invoke()
    {
        $args = func_get_args();
        $format = count($args) ? array_shift($args) : '%s';
        $content = str_replace(".", "-", vsprintf($format, $args));

        return $this->view->escapeHtml($content);
    }
}
