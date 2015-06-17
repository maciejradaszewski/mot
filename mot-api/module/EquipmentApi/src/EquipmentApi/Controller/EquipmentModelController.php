<?php

namespace EquipmentApi\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use EquipmentApi\Service\EquipmentModelService;
use EquipmentApi\Service\Mapper\EquipmentModelMapper;

/**
 * Class EquipmentModelController
 *
 * @package EquipmentApi\Controller
 */
class EquipmentModelController extends AbstractDvsaRestfulController
{

    private $mapper;

    public function __construct()
    {
        $this->mapper = new EquipmentModelMapper();
    }

    public function getList()
    {
        $equipmentModels = $this->getEquipmentService()->getAll();

        return ApiResponse::jsonOk($this->mapper->manyToDto($equipmentModels));
    }

    /**
     * @return EquipmentModelService
     */
    private function getEquipmentService()
    {
        return $this->getServiceLocator()->get(EquipmentModelService::class);
    }
}
