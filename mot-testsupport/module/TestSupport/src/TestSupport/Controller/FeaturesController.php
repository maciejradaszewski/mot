<?php

namespace TestSupport\Controller;

use TestSupport\Service\FeaturesService;
use Zend\View\Model\JsonModel;

class FeaturesController extends BaseTestSupportRestfulController
{
    protected $identifierName = 'featureName';

    public function get($featureName)
    {
        $featuresService = $this->getServiceLocator()->get(FeaturesService::class);
        return $featuresService->get($featureName);
    }
}