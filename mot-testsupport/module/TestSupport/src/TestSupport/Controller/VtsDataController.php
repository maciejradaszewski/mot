<?php

namespace TestSupport\Controller;

use Doctrine\ORM\EntityManager;
use DvsaCommon\UrlBuilder\UrlBuilder;
use DvsaCommon\Utility\ArrayUtils;
use TestSupport\FieldValidation;
use TestSupport\Helper\DataGeneratorHelper;
use TestSupport\Helper\RestClientGetterTrait;
use TestSupport\Helper\TestDataResponseHelper;
use Zend\Json\Server\Client;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Model\JsonModel;
use TestSupport\Service\VtsService;

/**
 * Creates VTSes (sites) for use by tests.
 *
 * Should not be deployed in production.
 */
class VtsDataController extends BaseTestSupportRestfulController
{

    /**
     * @param array $data
     *
     * @return JsonModel
     */
    public function create($data)
    {
        $vtsService = $this->getServiceLocator()->get(VtsService::class);
        return $vtsService->create($data);
    }


}
