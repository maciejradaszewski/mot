<?php

namespace EquipmentApi\Service;

use Doctrine\ORM\EntityRepository;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaEntities\Entity\EquipmentModel;

/**
 * Class EquipmentModelService
 */
class EquipmentModelService
{

    private $equipmentModelRepository;
    private $authService;

    public function __construct(EntityRepository $equipmentModelRepository, AuthorisationServiceInterface $authService)
    {
        $this->equipmentModelRepository = $equipmentModelRepository;
        $this->authService = $authService;
    }

    /**
     * @return EquipmentModel[]
     */
    public function getAll()
    {
        $this->authService->assertGranted(PermissionInSystem::MOT_CAN_VIEW_EQUIPMENT);

        return $this->equipmentModelRepository->findAll();
    }
}
