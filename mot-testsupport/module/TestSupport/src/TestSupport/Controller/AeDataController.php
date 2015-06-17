<?php

namespace TestSupport\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use DvsaCommon\Constants\OrganisationType;
use DvsaCommon\Enum\CompanyTypeName;
use DvsaCommon\UrlBuilder\UrlBuilder;
use DvsaCommon\Utility\ArrayUtils;
use TestSupport\Helper\DataGeneratorHelper;
use TestSupport\Helper\TestDataResponseHelper;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use TestSupport\Service\AEService;

/**
 * Creates AEs (organisations) for use by tests.
 *
 * Should not be deployed in production.
 */
class AeDataController extends BaseTestSupportRestfulController
{
    public function create($data)
    {
        $aeService = $this->getServiceLocator()->get(AEService::class);
        return $aeService->create($data);
    }

}
