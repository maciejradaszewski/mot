<?php

namespace Dvsa\Mot\Frontend\Plugin;

use Zend\Http\Response;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\Exception;
use Zend\Mvc\InjectApplicationEventInterface;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;

/**
 *
 * A helper that is to unify AJAX communication between the client and the web layer.
 *
 * Class AjaxResponse
 *
 * @package Dvsa\Mot\Frontend\Plugin
 */
class AjaxResponsePlugin extends AbstractPlugin
{


    /**
     * Generates JSON redirect model based on route. Intentionally 302 response
     * code is not set to enable client side to detect redirect event.
     *
     * @param string $route   RouteInterface name
     * @param array  $params  Parameters to use in url generation, if any
     * @param array  $options query parameter to be appended to the url
     *
     * @return JsonModel
     * @throws \Zend\Mvc\Exception\DomainException
     */
    public function redirectToRoute($route = null, $params = [], $options = [])
    {
        $controller = $this->getController();
        if (!$controller || !method_exists($controller, 'plugin')) {
            throw new Exception\DomainException(
                'AjaxResponse plugin requires a controller that defines the plugin() method'
            );
        }
        $urlPlugin = $controller->plugin('url');
        $url = $urlPlugin->fromRoute($route, $params, $options);

        return $this->redirectToUrl($url);
    }

    /**
     * Generate JSON redirect model based on url
     *
     * @param $url
     *
     * @return JsonModel
     */
    public function redirectToUrl($url)
    {
        return new JsonModel(["redirectUrl" => $url]);
    }

    /**
     * Generate succesful JSON response
     *
     * @param $data
     *
     * @return JsonModel
     */
    public function ok($data)
    {
        return new JsonModel(["data" => $data]);
    }
}
