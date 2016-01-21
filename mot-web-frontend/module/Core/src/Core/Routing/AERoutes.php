<?php

namespace Core\Routing;

use Zend\Mvc\Controller\AbstractController;
use Zend\View\Helper\Url;
use Zend\View\Renderer\PhpRenderer;

class AERoutes
{
    private $urlHelper;

    public function __construct(Url $urlHelper)
    {
        $this->urlHelper = $urlHelper;
    }

    public function ae($id)
    {
        return $this->url(AERouteList::AE, ['id' => $id]);
    }

    private function url($name = null, $params = array(), $options = array(), $reuseMatchedParams = false)
    {
        $helper = $this->urlHelper;
        return $helper($name, $params, $options, $reuseMatchedParams);
    }

    public static function of($object)
    {
        $urlHelper = null;

        if ($object instanceof Url) {
            $urlHelper = $object;
        } elseif ($object instanceof PhpRenderer) {
            $urlHelper = $object->plugin('url');
        } elseif ($object instanceof AbstractController) {
            $urlHelper = $object->plugin('url');
        } else {
            throw new \InvalidArgumentException("First parameter must be of class: " .
                Url::class . ", " . PhpRenderer::class . " or " . AbstractController::class);
        }

        return new AERoutes($urlHelper);
    }
}
