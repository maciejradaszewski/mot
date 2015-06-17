<?php

namespace TestSupport\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use TestSupport\Service\TesterService;

/**
 * Creates testers for use by tests.
 *
 * Should not be deployed in production.
 */
class TesterDataController extends BaseTestSupportRestfulController
{
    /**
     * @param mixed $data including "siteIds" -> list of VTSs in which to create an active tester,
     *                    "diff" string to differentiate testers
     *                    optional "testGroup", to create a tester restricted to certain group (1 => 1,2; 2 => 3,4,5,7),
     *                    optional "status" e.g. SPND - suspended. Default is QLFD - qualified
     *
     * @return JsonModel
     */
    public function create($data)
    {
        $testerService = $this->getServiceLocator()->get(TesterService::class);
        $resultJson = $testerService->create($data);
        return $resultJson;
    }


}
