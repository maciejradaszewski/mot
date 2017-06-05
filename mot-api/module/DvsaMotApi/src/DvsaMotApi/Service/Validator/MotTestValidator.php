<?php

namespace DvsaMotApi\Service\Validator;

use CensorApi\Service\CensorService;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Constants\ReasonForRejection;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Messages\InvalidTestStatus;
use DvsaCommonApi\Service\Exception\BadRequestException;
use DvsaCommonApi\Service\Exception\ForbiddenException;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaCommonApi\Service\Exception\RequiredFieldException;
use DvsaCommonApi\Service\Validator\AbstractValidator;
use DvsaEntities\Entity\FuelType;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\MotTestReasonForRejection;
use DvsaEntities\Entity\VehicleClass;
use DvsaFeature\FeatureToggles;
use UserApi\SpecialNotice\Service\SpecialNoticeService;
use Zend\Authentication\AuthenticationService;

/**
 * Class MotTestValidator.
 */
class MotTestValidator extends AbstractValidator
{
    const ERROR_MSG_OUT_OF_SLOTS = 'You do not have slots to perform an MOT Test';
    const ERROR_MSG_INVALID_TESTER = 'You cannot make changes to this test';
    const ERROR_MSG_NOT_VALID_TO_TEST_VEHICLE_CLASS = 'You are not authorised to test a class %s vehicle';
    const ERROR_MSG_OVERDUE_SPECIAL_NOTICES = 'You are not authorised to test a class %s vehicle. Test status will become active when unacknowledged notices have been read and confirmed';
    const ERROR_MSG_NOT_VALID_SITE_TO_TEST_VEHICLE_CLASS = 'Your Site is not authorised to test class %s vehicles';
    const ERROR_MSG_NOT_FOUND_SITE_FOR_TESTER = 'Site not found for Tester';
    const ERROR_MSG_NOT_FOUND_FUEL_TYPE = 'Fuel Type not found';
    const ERROR_MSG_REQUIRED_TESTER = 'Tester is required';
    const ERROR_MSG_REQUIRED_VEHICLE = 'Vehicle is required';
    const ERROR_MSG_REQUIRED_VTS = 'Vehicle testing station is required';
    const ERROR_MSG_REQUIRED_FUEL_TYPE = 'Fuel type is required';
    const ERROR_MSG_REQUIRED_VEHICLE_CLASS = 'Vehicle class is required';

    /** @var CensorService */
    private $censorService;

    /** @var AuthorisationServiceInterface $authorizationService */
    private $authorizationService;

    /** @var AuthenticationService $motIdentityProvider */
    private $motIdentityProvider;

    /** @var SpecialNoticeService */
    private $specialNoticeService;

    /** @var FeatureToggles $featureToggles */
    private $featureToggles;

    public function __construct(
        CensorService $censorService,
        AuthorisationServiceInterface $authorizationService,
        AuthenticationService $motIdentityProvider,
        SpecialNoticeService $specialNoticeService,
        FeatureToggles $featureToggles
    ) {
        $this->censorService = $censorService;
        parent::__construct();
        $this->authorizationService = $authorizationService;
        $this->motIdentityProvider = $motIdentityProvider;
        $this->specialNoticeService = $specialNoticeService;
        $this->featureToggles = $featureToggles;
    }

    public function validateNewMotTest(MotTest $motTest)
    {
        $this->checkRequiredForNew($motTest);

        if (!$motTest->getMotTestType()->getIsDemo()) {
            $this->checkMotTestTesterHasSlotsToPerformMotTest($motTest);
            $this->checkVehicleIsValidToTest($motTest);
        }
    }

    public function assertCanBeUpdated(MotTest $motTest)
    {
        if (!$motTest->isActive()) {
            throw new ForbiddenException(InvalidTestStatus::getMessage($motTest->getStatus()));
        } elseif ($this->motIdentityProvider->getIdentity()->getUserId() !== $motTest->getTester()->getId()
            && !$this->authorizationService->isGranted(PermissionInSystem::VE_MOT_TEST_ABORT)
        ) {
            throw new ForbiddenException(self::ERROR_MSG_INVALID_TESTER);
        }
    }

    public function assertCanBeAborted(MotTest $motTest)
    {
        if ($motTest->getMotTestType()->getIsDemo()) {
            return true;
        }

        if ($motTest->getMotTestType()->isNonMotTest()) {
            return true;
        }

        if ($motTest->getMotTestType()->getIsReinspection()) {
            $this->authorizationService->assertGranted(PermissionInSystem::VE_MOT_TEST_ABORT);
        }

        $this->authorizationService->assertGrantedAtSite(
            PermissionAtSite::MOT_TEST_ABORT_AT_SITE,
            $motTest->getVehicleTestingStation()->getId()
        );
    }

    public function validateMotTestReasonForRejection(MotTestReasonForRejection $rfr)
    {
        if ($rfr->getReasonForRejection() === null
            && ($rfr->getCustomDescription() === null || strlen($rfr->getCustomDescription()->getCustomDescription()) == 0)
        ) {
            throw new BadRequestException('You must give a description', BadRequestException::ERROR_CODE_INVALID_DATA);
        }

        $description = ($rfr->getCustomDescription()) ? $rfr->getCustomDescription()->getCustomDescription() : '';

        if ($this->censorService->containsProfanity($description)
            || $this->censorService->containsProfanity($rfr->getComment())
        ) {
            throw new BadRequestException(
                'Additional information – must not include any swearwords',
                BadRequestException::ERROR_CODE_INVALID_DATA,
                'Must not include any swearwords'
            );
        }

        $customDescription = $rfr->getCustomDescription();
        $isCustomDescriptionTooLong = $customDescription !== null &&
            strlen($customDescription->getCustomDescription()) > ReasonForRejection::MAX_USER_COMMENT_LENGTH;

        if ($isCustomDescriptionTooLong || strlen($rfr->getComment()) > ReasonForRejection::MAX_USER_COMMENT_LENGTH) {
            throw new BadRequestException(
                'Additional information – must be 250 characters or shorter',
                BadRequestException::ERROR_CODE_INVALID_DATA,
                'Must be 250 characters or shorter'
            );
        }

        if ($rfr->getOnOriginalTest()) {
            throw new BadRequestException(
                'Original RFR cannot be changed',
                BadRequestException::ERROR_CODE_INVALID_DATA
            );
        }

        if ($rfr->getReasonForRejection() !== null) {
            $endDate = $rfr->getReasonForRejection()->getEndDate();
            if ($endDate !== null && $endDate <= DateUtils::today()) {
                throw new BadRequestException(
                    'End-dated RFR can not be added',
                    BadRequestException::ERROR_CODE_INVALID_DATA
                );
            }

            $currentVehicleClass = $rfr->getMotTest()->getVehicleClass();
            if (!$rfr->getReasonForRejection()->isApplicableToVehicleClass($currentVehicleClass)) {
                throw new BadRequestException(
                    'This RFR cannot be added to a vehicle of this class',
                    BadRequestException::ERROR_CODE_INVALID_DATA
                );
            }
        }

        return true;
    }

    protected function checkRequiredForNew(MotTest $motTest)
    {
        $this->checkRequiredTester($motTest);

        $this->checkRequiredVehicle($motTest);

        $this->checkRequiredVehicleTestingStation($motTest);

        $this->checkRequiredFuelType($motTest);

        $this->checkRequiredVehicleClass($motTest);

        $this->errors->throwIfAny();

        $missingRequiredFields = [];
        if ($motTest->getPrimaryColour() === null) {
            $missingRequiredFields[] = 'Primary Colour';
        }
        if ($motTest->getSecondaryColour() === null) {
            $missingRequiredFields[] = 'Secondary Colour';
        }
        if ($motTest->getHasRegistration() === null) {
            $missingRequiredFields[] = 'Has Registration';
        }

        if (count($missingRequiredFields) > 0) {
            throw new RequiredFieldException($missingRequiredFields);
        }
    }

    private function checkRequiredTester(MotTest $motTest)
    {
        if ($this->isNull($motTest->getTester())) {
            $this->errors->add(self::ERROR_MSG_REQUIRED_TESTER);
        }
    }

    private function checkRequiredVehicle($motTest)
    {
        if ($this->isNull($motTest->getVehicle())) {
            $this->errors->add(self::ERROR_MSG_REQUIRED_VEHICLE, 'vehicleId');
        }
    }

    private function checkRequiredVehicleTestingStation(MotTest $motTest)
    {
        if ($this->isNull($motTest->getVehicleTestingStation())
            && !($motTest->getMotTestType()->getIsDemo() || $motTest->getMotTestType()->isNonMotTest())
        ) {
            $this->errors->add(self::ERROR_MSG_REQUIRED_VTS, 'vehicleTestingStationId');
        }
    }

    private function checkRequiredFuelType(MotTest $motTest)
    {
        if (!$motTest->getFuelType() instanceof FuelType) {
            $this->errors->add(self::ERROR_MSG_REQUIRED_FUEL_TYPE, 'fuelTypeId');
            throw new BadRequestException(self::ERROR_MSG_NOT_FOUND_FUEL_TYPE, NotFoundException::ERROR_CODE_NOT_FOUND);
        }
    }

    private function checkRequiredVehicleClass(MotTest $motTest)
    {
        if (!$motTest->getVehicleClass() instanceof VehicleClass) {
            $this->errors->add(self::ERROR_MSG_REQUIRED_VEHICLE_CLASS, 'vehicleClassCode');
        }
    }

    private function hasSlotsToPerformMotTest(MotTest $motTest)
    {
        $org = $motTest->getVehicleTestingStation()->getOrganisation();
        $slots = is_object($org) ? $org->getSlotBalance() : 0;

        return is_int($slots) && $slots > 0;
    }

    protected function checkMotTestTesterHasSlotsToPerformMotTest(MotTest $motTest)
    {
        $areSlotsRequired = $motTest->getMotTestType()->getIsSlotConsuming();

        if ($areSlotsRequired && !$this->hasSlotsToPerformMotTest($motTest)) {
            throw new ForbiddenException(self::ERROR_MSG_OUT_OF_SLOTS);
        }
    }

    protected function checkVehicleIsValidToTest(MotTest $motTest)
    {
        /** @var \DvsaEntities\Entity\Person $person */
        $person = $motTest->getTester();
        /*
         * If a Vehicle Examiner is doing the test then no right for vehicle class is required.
         */
        if ($this->authorizationService->personHasRole($person, \DvsaCommon\Constants\Role::VEHICLE_EXAMINER)) {
            return;
        }

        /** @var VehicleClass $vehicleClassEntity */
        $vehicleClassEntity = $motTest->getVehicleClass();
        $vehicleClass = $vehicleClassEntity->getCode();

        if (!$person->isQualifiedTesterForVehicleClass($vehicleClassEntity)) {
            throw new ForbiddenException(sprintf(self::ERROR_MSG_NOT_VALID_TO_TEST_VEHICLE_CLASS, $vehicleClass));
        }

        if (!$motTest->getVehicleTestingStation()->hasAuthForVehicleClass($vehicleClassEntity)) {
            throw new ForbiddenException(sprintf(self::ERROR_MSG_NOT_VALID_SITE_TO_TEST_VEHICLE_CLASS, $vehicleClass));
        }
    }
}
