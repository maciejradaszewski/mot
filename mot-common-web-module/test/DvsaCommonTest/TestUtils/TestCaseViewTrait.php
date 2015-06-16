<?php

namespace DvsaCommonTest\TestUtils;

use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver as Resolver;

trait TestCaseViewTrait
{
    protected function getPhpRenderer(array $templates)
    {
        $renderer = new PhpRenderer();

        $resolver = new Resolver\AggregateResolver();
        $renderer->setResolver($resolver);

        $mapResolver = new Resolver\TemplateMapResolver($templates);
        $resolver->attach($mapResolver);

        return $renderer;
    }
}
