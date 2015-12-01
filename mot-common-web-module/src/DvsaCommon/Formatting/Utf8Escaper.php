<?php

namespace DvsaCommon\Formatting;

use Zend\Escaper\Escaper;

class Utf8Escaper extends Escaper
{
    public function __construct()
    {
        parent::__construct('UTF-8');
    }
}
