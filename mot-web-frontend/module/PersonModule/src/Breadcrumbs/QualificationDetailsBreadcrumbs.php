<?php
namespace Dvsa\Mot\Frontend\PersonModule\Breadcrumbs;

use Zend\Mvc\Controller\AbstractActionController;

class QualificationDetailsBreadcrumbs extends PersonProfileBreadcrumbs
{
    const ROUTE_QUALIFICATION_DETAILS = '/qualification-details';
    const QUALIFICATION_DETAILS_BREADCRUMB = 'Qualification details';

    public function getBreadcrumbs($personId, AbstractActionController $controller, $currentStep = null)
    {
        $breadcrumbs = parent::getBreadcrumbs($personId, $controller, null);

        $breadcrumbs += [
            static::QUALIFICATION_DETAILS_BREADCRUMB => $controller->url()->fromRoute($this->getRoute(), $controller->params()->fromRoute()),
        ];

        if(!empty($currentStep)) {
            $breadcrumbs+=[
                $currentStep => '',
            ];
        }

        return $breadcrumbs;
    }

    public function getRoute()
    {
        $route = parent::getRoute();
        return $route . static::ROUTE_QUALIFICATION_DETAILS;
    }
}