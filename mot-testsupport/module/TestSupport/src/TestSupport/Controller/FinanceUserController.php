<?php

namespace TestSupport\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use TestSupport\Service\FinanceUserService;

class FinanceUserController extends BaseTestSupportRestfulController
{
    public function create($data)
    {
        $service = $this->getServiceLocator()->get(FinanceUserService::class);
        $resultJson = $service->create($data);
        return $resultJson;
    }
}
