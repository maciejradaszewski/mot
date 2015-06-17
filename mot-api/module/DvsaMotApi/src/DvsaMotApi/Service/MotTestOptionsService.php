<?php

namespace DvsaMotApi\Service;

use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Dto\MotTesting\MotTestOptionsDto;
use DvsaCommonApi\Authorisation\Assertion\ReadMotTestAssertion;
use DvsaEntities\Repository\MotTestRepository;

class MotTestOptionsService
{
    /** @var MotTestRepository $motTestRepository */
    private $motTestRepository;
    /** @var MotAuthorisationServiceInterface $authorisationService */
    private $authorisationService;

    private $readMotTestAssertion;

    public function __construct(
        MotTestRepository $motTestRepository,
        MotAuthorisationServiceInterface $authorisationService,
        ReadMotTestAssertion $readTestAssertion
    ) {
        $this->motTestRepository = $motTestRepository;
        $this->authorisationService = $authorisationService;
        $this->readMotTestAssertion = $readTestAssertion;
    }

    /**
     * @param string $motTestNumber
     *
     * @return MotTestOptionsDto
     *
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     */
    public function getOptions($motTestNumber)
    {
        $motTest = $this->motTestRepository->getMotTestByNumber($motTestNumber);

        $this->readMotTestAssertion->assertGranted($motTest);

        $vehicle = $motTest->getVehicle();

        return (new MotTestOptionsDto())
            ->setMotTestStartedDate(DateTimeApiFormat::dateTime($motTest->getStartedDate()))
            ->setVehicleMake($vehicle->getMakeName())
            ->setVehicleModel($vehicle->getModelName())
            ->setVehicleRegistrationNumber($vehicle->getRegistration());
    }
}
