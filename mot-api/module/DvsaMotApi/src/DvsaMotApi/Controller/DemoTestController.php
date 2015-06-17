<?php
namespace DvsaMotApi\Controller;

use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Constants\Network;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use DvsaMotApi\Controller\Validator\CreateMotTestRequestValidator;
use DvsaMotApi\Service\MotTestService;

/**
 * Api controller for creating a new DEMO Test
 */
class DemoTestController extends AbstractDvsaRestfulController
{
    //region post fields
    const FIELD_VEHICLE_ID = 'vehicleId';
    const FIELD_PRIMARY_COLOUR = 'primaryColour';
    const FIELD_SECONDARY_COLOUR = 'secondaryColour';
    const FIELD_FUEL_TYPE_ID = 'fuelTypeId';
    const FIELD_VEHICLE_CLASS_CODE = "vehicleClassCode";
    const FIELD_HAS_REGISTRATION = 'hasRegistration';
    const FIELD_MOT_TEST_TYPE = 'motTestType';
    const FIELD_MOT_TEST_ID_ORIGINAL = 'motTestIdOriginal';
    const FIELD_ONE_TIME_PASSWORD = 'oneTimePassword';
    const FIELD_ID = 'id';
    //endregion

    public function create($data)
    {
        CreateMotTestRequestValidator::validateDemo($data);

        $vehicleId = (string)$data[self::FIELD_VEHICLE_ID];
        $primaryColour = $data[self::FIELD_PRIMARY_COLOUR];

        $secondaryColour = ArrayUtils::tryGet($data, self::FIELD_SECONDARY_COLOUR);
        $fuelTypeId = ArrayUtils::tryGet($data, self::FIELD_FUEL_TYPE_ID);
        $vehicleClassCode = ArrayUtils::tryGet($data, self::FIELD_VEHICLE_CLASS_CODE);
        $hasRegistration = $data[self::FIELD_HAS_REGISTRATION] === true;
        $oneTimePassword = ArrayUtils::tryGet($data, self::FIELD_ONE_TIME_PASSWORD);

        $motTestTypeCode = MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING;

        $person = $this->getIdentity()->getPerson();

        $motTest = $this->getService()
            ->createMotTest(
                $person,
                $vehicleId,
                null,
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
                $oneTimePassword
            );

        return ApiResponse::jsonOk(["motTestNumber" => $motTest->getNumber()]);
    }

    /**
     * @return MotTestService
     */
    private function getService()
    {
        return $this->getServiceLocator()->get('MotTestService');
    }
}
