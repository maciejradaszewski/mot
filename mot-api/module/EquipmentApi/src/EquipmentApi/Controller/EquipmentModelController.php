<?php

namespace EquipmentApi\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use EquipmentApi\Service\EquipmentModelService;
use EquipmentApi\Mapper\EquipmentModelMapper;

/**
 * Class EquipmentModelController
 *
 * @package EquipmentApi\Controller
 */
class EquipmentModelController extends AbstractDvsaRestfulController
{
    private $equipmentModelService;

    public function __construct(EquipmentModelService $service)
    {
        $this->equipmentModelService = $service;
    }

    public function getList()
    {
        $equipmentModelDto = $this->equipmentModelService->getAll();

        return ApiResponse::jsonOk($equipmentModelDto);
    }
}
