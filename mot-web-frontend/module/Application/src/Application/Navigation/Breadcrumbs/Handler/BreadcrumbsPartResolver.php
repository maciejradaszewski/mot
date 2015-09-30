<?php

namespace Application\Navigation\Breadcrumbs\Handler;

use Zend\View\Helper\Url;

abstract class BreadcrumbsPartResolver
{
    abstract public function resolve($data);
}
