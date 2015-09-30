<?php

namespace Application\Navigation\Breadcrumbs\Handler;

class SimpleResolver extends BreadcrumbsPartResolver
{
    public function __construct($urlHelper)
    {
        $this->urlHelper = $urlHelper;
    }

    public function resolve($data)
    {
        $link = '';
        if (isset($data['link']['route'])) {
            $route = $data['link']['route'];
            $params = $data['link']['params'];
            $urlHelper = $this->urlHelper;
            $link = $urlHelper($route, $params);
        }

        return [$data['label'] => $link];
    }
}
