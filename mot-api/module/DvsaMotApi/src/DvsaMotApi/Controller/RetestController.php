<?php
namespace DvsaMotApi\Controller;

use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommon\Constants\Network;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use DvsaMotApi\Service\MotTestService;

/**
 * Class RetestController
 */
class RetestController extends AbstractDvsaRestfulController
{
    const FIELD_VEHICLE_ID = 'vehicleId';
    const FIELD_VTS_ID = 'vehicleTestingStationId';
    const FIELD_PRIMARY_COLOUR = 'primaryColour';
    const FIELD_SECONDARY_COLOUR = 'secondaryColour';
    const FIELD_FUEL_TYPE_ID = 'fuelTypeId';
    const FIELD_VEHICLE_CLASS_CODE = "vehicleClassCode";
    const FIELD_HAS_REGISTRATION = 'hasRegistration';
    const FIELD_MOT_TEST_TYPE = 'motTestType';
    const FIELD_MOT_TEST_ID_ORIGINAL = 'motTestIdOriginal';
    const FIELD_ONE_TIME_PASSWORD = 'oneTimePassword';
    const SITE_NUMBER_QUERY_PARAMETER = 'siteNumber';
    const VEHICLE_ID_QUERY_PARAMETER = 'vehicleId';
    const SITE_NUMBER_REQUIRED_MESSAGE = 'Query parameter siteNumber is required';
    const SITE_NUMBER_REQUIRED_DISPLAY_MESSAGE = 'You need to enter siteNumber to search for MOTs';
    const FIELD_ID = 'id';
    const FIELD_CONTINGENCY = 'contingencyId';
    const FIELD_CONTINGENCY_DTO = 'contingencyDto';

    protected $nominatedTester = null;

    public function create($data)
    {
        $errors = [];
        $errors = $this->checkForRequiredFieldAndAddToErrors(self::FIELD_VEHICLE_ID, $data, $errors);
        $errors = $this->checkForRequiredFieldAndAddToErrors(self::FIELD_VTS_ID, $data, $errors);
        $errors = $this->checkForRequiredFieldAndAddToErrors(self::FIELD_PRIMARY_COLOUR, $data, $errors);
        $errors = $this->checkForRequiredFieldAndAddToErrors(self::FIELD_HAS_REGISTRATION, $data, $errors);

        if (count($errors) > 0) {
            return $this->returnBadRequestResponseModelWithErrors($errors);
        }

        $person = $this->getIdentity()->getPerson();

        $vehicleId = (string)$data[self::FIELD_VEHICLE_ID];
        $vehicleTestingStationId = (int)$data[self::FIELD_VTS_ID];
        $primaryColour = $data[self::FIELD_PRIMARY_COLOUR];
        $secondaryColour = ArrayUtils::tryGet($data, self::FIELD_SECONDARY_COLOUR);
        $fuelTypeId = ArrayUtils::tryGet($data, self::FIELD_FUEL_TYPE_ID);
        $vehicleClassCode = ArrayUtils::tryGet($data, self::FIELD_VEHICLE_CLASS_CODE);
        $hasRegistration = ($data[self::FIELD_HAS_REGISTRATION] === true);
        $oneTimePassword = ArrayUtils::tryGet($data, self::FIELD_ONE_TIME_PASSWORD);
        $contingencyId = ArrayUtils::tryGet($data, self::FIELD_CONTINGENCY);
        $contingencyDto = ArrayUtils::tryGet($data, self::FIELD_CONTINGENCY_DTO);

        if (!is_null($contingencyDto)) {
            $contingencyDto = DtoHydrator::jsonToDto($contingencyDto);
        }

        $motTestTypeCode = MotTestTypeCode::RE_TEST;

        /** @var MotTestService $motTestService */
        $motTestService = $this->getServiceLocator()->get('MotTestService');

        $motTest = $motTestService
            ->createMotTest(
                $person,
                $vehicleId,
                $vehicleTestingStationId,
                $primaryColour,
                $secondaryColour,
                $fuelTypeId,
                $vehicleClassCode,
                $hasRegistration,
                null,
                $motTestTypeCode,
                null,
                null,
                false,
                $oneTimePassword,
                $contingencyId,
                $contingencyDto
            );

        return ApiResponse::jsonOk(["motTestNumber" => $motTest->getNumber()]);
    }
}

