<?php

namespace SiteApi\Service\Validator;


use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommon\Enum\SiteTypeCode;
use DvsaCommonApi\Service\Validator\AbstractValidator;

class SiteDetailsValidator extends AbstractValidator
{
    const FIELD_LOCATION_TYPE = 'location';
    const FIELD_VEHICLE_CLASS = 'classes';
    const FIELD_STATUS = 'status';

    const ERR_LOCATION_TYPE_REQUIRE = 'A location type must be selected';
    const ERR_VEHICLE_CLASS_REQUIRE = '1 or more vehicle classes must be selected';
    const ERR_STATUS_REQUIRE = 'Site status must be selected';


    /**
     * Validates basic site details
     *
     * @param VehicleTestingStationDto $siteDto
     * @param bool $checkType
     * @param bool $checkStatus
     * @throws \DvsaCommonApi\Service\Exception\BadRequestException
     * @internal param bool $update
     */
    public function validate(VehicleTestingStationDto $siteDto, $checkType = true, $checkStatus = true)
    {
        if(true == $checkType){
            $this->validateType($siteDto);
        }

        if(true == $checkStatus){
            $this->validateStatus($siteDto);
        }

        $this->errors->throwIfAnyField();
    }

    /**
     * @param VehicleTestingStationDto $siteDto
     */
    public function validateType(VehicleTestingStationDto $siteDto)
    {
        if ($this->isEmpty(trim($siteDto->getType())) || SiteTypeCode::exists($siteDto->getType()) === false) {
            $this->errors->add(self::ERR_LOCATION_TYPE_REQUIRE, self::FIELD_LOCATION_TYPE);
        }
    }

    /**
     * @param VehicleTestingStationDto $siteDto
     */
    public function validateStatus(VehicleTestingStationDto $siteDto)
    {
        $status = $siteDto->getStatus();

        if (empty($status) || !in_array($status, $this->getAllowedStatuses())){
            $this->errors->add(self::ERR_STATUS_REQUIRE, self::FIELD_STATUS);
        }
    }

    private function getAllowedStatuses()
    {
        return [
            'AV',
            'AP',
            'RE',
            'RJ',
            'LA',
            'EX',
        ];
    }

}