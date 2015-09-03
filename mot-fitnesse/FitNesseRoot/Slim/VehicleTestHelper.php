<?php

use DvsaCommon\Enum\VehicleClassCode;
use MotFitnesse\Util\UrlBuilder;
use DvsaCommon\Enum\ColourCode;
use MotFitnesse\Util\TestSupportUrlBuilder;
use DvsaCommon\Enum\CountryOfRegistrationCode;
use DvsaCommon\Enum\FuelTypeCode;

/**
 * Calls API for generating new vehicles
 */
class VehicleTestHelper
{
    private static $lastGenSuffix = "1AAAA";

    private $api;

    public function __construct(FitMotApiClient $api)
    {
        $this->api = $api;
    }

    /**
     * @param array $spec
     *
     * @return int created vehicle Id
     */
    public function generateVehicle($spec = ['testClass' => VehicleClassCode::CLASS_4])
    {
        $vrm = 'FIT' . self::$lastGenSuffix;
        $vin = 'FITVINNUMBER' . self::$lastGenSuffix;
        $new10BaseSuffix = (int)base_convert(self::$lastGenSuffix, 36, 10) + 1;
        self::$lastGenSuffix = base_convert($new10BaseSuffix, 10, 36);

        $data = [
            'registrationNumber'    => $vrm,
            'bodyType'              => '12',
            'vin'                   => $vin,
            'make'                  => '18811',
            'makeOther'             => '',
            'model'                 => '01459',
            'modelOther'            => '',
            'modelType'             => '',
            'colour'                => ColourCode::ORANGE,
            'secondaryColour'       => ColourCode::BLACK,
            'dateOfFirstUse'        => '1999-01-01',
            'dateOfManufacture'     => '1999-01-01',
            'firstRegistrationDate' => '1999-01-01',
            'fuelType'              => FuelTypeCode::PETROL,
            'testClass'             => VehicleClassCode::CLASS_4,
            'countryOfRegistration' => CountryOfRegistrationCode::GBG_GG_GUERNSEY,
            'cylinderCapacity'      => 1234,
            'transmissionType'      => 'a',
            'oneTimePassword'       => '123456',
            'returnOriginalId'      => 'true'
        ];

        if (!empty($spec)) {
            $data = array_merge($data, $spec);
        }

        return $this->api->post((new TestSupportUrlBuilder())->vehicleCreate(), $data);
    }

    public function generateV5c(
        $vehicleId,
        $v5cRef,
        \DateTime $firstSeen = null,
        \DateTime $lastSeen = null,
        $mot1LegacyId = null
    ) {
        $inputData = [
            'vehicleId'    => $vehicleId,
            'v5cRef'       => $v5cRef,
            'firstSeen'    => $firstSeen,
            'lastSeen'     => $lastSeen,
            'mot1LegacyId' => $mot1LegacyId,
        ];

        return $this->api->post((new TestSupportUrlBuilder())->vehicleAddV5c(), $inputData);
    }

    /**
     * @param array $spec
     *
     * @return int created dvla vehicle Id
     */
    public function generateDvlaVehicle($spec = [])
    {
        $vrm = 'FIT' . self::$lastGenSuffix;
        $vin = 'FITVINNUMBER' . self::$lastGenSuffix;
        $new10BaseSuffix = (int)base_convert(self::$lastGenSuffix, 36, 10) + 1;
        self::$lastGenSuffix = base_convert($new10BaseSuffix, 10, 36);

        $data = [
            'registration'    => $vrm,
            'registration_validation_character' => '+',
            'vin'                   => $vin,
            'model_code'                  => '',
            'make_code'             => '',
            'make_in_full'                 => '',
            'colour_1_code' => 'S',
            'colour_2_code' => 'S',
            'propulsion_code' => '1',
            'designed_gross_weight' => '1',
            'unladen_weight' => '1327',
            'engine_number' => 'BBB 1231231',
            'engine_capacity' => '6590',
            'seating_capacity' => '5',
            'manufacture_date' => '2015-01-01',
            'is_seriously_damaged' => '0',
            'recent_v5_document_number' => '12312312312',
            'is_vehicle_new_at_first_registration' => '1',
            'body_type_code' => 'h',
            'wheelplan_code' => 'C'
        ];

        if (!empty($spec)) {
            $data = array_merge($data, $spec);
        }

        return $this->api->post((new TestSupportUrlBuilder())->testSupport()->createDvlaVehicle(), $data);
    }
}
