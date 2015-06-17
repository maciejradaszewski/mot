<?php
/**
 * Created by PhpStorm.
 * User: maciejsz
 * Date: 12/02/2014
 * Time: 12:00
 */

namespace Dvsa\Mot\Frontend\ViewHelper;

use Zend\View\Helper\AbstractHelper;

class WithDefault extends AbstractHelper
{
    private $default;

    public function __invoke($default = null) {
        $this->default = $default;
        return $this;
    }

    public function getValue(&$val) {
        return isset($val) ? $val : $this->default;
    }
}
