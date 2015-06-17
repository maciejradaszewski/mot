<?php

namespace Dvsa\Mot\Frontend;

use Zend\View\Model\ViewModel;

class AjaxModel extends ViewModel
{
    public function __construct($variables = null, $options = null)
    {
        parent::__construct($variables, $options);

        $this->setTerminal(true);
    }
}
