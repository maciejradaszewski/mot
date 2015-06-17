<?php

namespace DvsaMotApi\Service;

use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use Api\Check\CheckResultExceptionTranslator;
use DvsaCommonApi\Authorisation\Assertion\ApiPerformMotTestAssertion;
use DvsaCommon\Dto\Common\OdometerReadingDTO;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\OdometerReading;
use DvsaEntities\Repository\OdometerReadingRepository;
use DvsaMotApi\Service\Validator\MotTestValidator;
use DvsaMotApi\Service\Validator\Odometer\OdometerReadingValidator;

/**
 *
 * Class OdometerReadingUpdatingService
 *
 * @package DvsaMotApi\Service
 */
class OdometerReadingUpdatingService
{

    /**
     * @var OdometerReadingRepository
     */
    private $readingRepository;

    /**
     * @var AuthorisationServiceInterface
     */
    private $authService;
    /**
     * @var MotTestSecurityService $motTestSecurityService
     */
    private $motTestSecurityService;

    /** @var  MotTestValidator $motTestValidator */
    private $motTestValidator;

    private $performMotTestAssertion;

    /**
     * @param OdometerReadingRepository  $odometerReadingRepository
     * @param AuthorisationServiceInterface       $authService
     * @param MotTestSecurityService     $motTestSecurityService
     * @param Validator\MotTestValidator $motTestValidator
     */
    public function __construct(
        OdometerReadingRepository $odometerReadingRepository,
        AuthorisationServiceInterface $authService,
        MotTestSecurityService $motTestSecurityService,
        MotTestValidator $motTestValidator,
        ApiPerformMotTestAssertion $performMotTestAssertion
    ) {
        $this->readingRepository = $odometerReadingRepository;
        $this->authService = $authService;
        $this->motTestSecurityService = $motTestSecurityService;
        $this->motTestValidator = $motTestValidator;
        $this->performMotTestAssertion = $performMotTestAssertion;
    }

    /**
     * Updates odometer reading for a given MOT test
     *
     * @param OdometerReadingDTO $newReading
     * @param MotTest            $motTest
     *
     * @throws \DvsaCommonApi\Service\Exception\ForbiddenException
     */
    public function updateForMotTest(OdometerReadingDTO $newReading, MotTest $motTest)
    {
        $this->performMotTestAssertion->assertGranted($motTest);

        $this->motTestValidator->assertCanBeUpdated($motTest);
        //TODO: dependency should be injected #przemek
        $checkResult = (new OdometerReadingValidator())->validate($newReading);
        CheckResultExceptionTranslator::tryThrowDataValidationException($checkResult);

        /** NEED TO FIGURE OUT RULES
        if (!$this->motTestSecurityService->canModifyOdometerForTest($motTest)
            || !$this->motTestSecurityService->isCurrentTesterAssignedToVts(
                $motTest->getVehicleTestingStation()->getId()
            )
        ) {
            throw new ForbiddenException("You are not allowed to change odometer reading");
        }
        **/

        $newReadingEntity = OdometerReading::create()
            ->setValue($newReading->getValue())
            ->setUnit($newReading->getUnit())
            ->setResultType($newReading->getResultType());

        $motTest->setOdometerReading($newReadingEntity);
    }
}
