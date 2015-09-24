<?php

namespace SiteApi\Service\Validator;

use DvsaCommon\Constants\FacilityTypeCode;
use DvsaCommon\Dto\Site\SiteContactDto;
use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommon\Enum\SiteTypeCode;
use DvsaCommonApi\Service\Validator\AbstractValidator;
use DvsaCommonApi\Service\Validator\ValidatorInterface;
use DvsaEntities\Entity\Vehicle;
use OrganisationApi\Service\Validator\ContactValidator;

class SiteValidator extends AbstractValidator
{
    const FIELD_VEHICLE_CLASS = 'classes';
    const FIELD_LOCATION_TYPE = 'location';
    const FIELD_TESTING_FACILITY_OPTL = 'facilityOptl';
    const FIELD_TESTING_FACILITY_TPTL = 'facilityTptl';

    const ERR_LOCATION_TYPE_REQUIRE = 'A location type must be selected';
    const ERR_VEHICLE_CLASS_REQUIRE = '1 or more vehicle classes must be selected';
    const ERR_TESTING_FACILITY_OPTL_REQUIRE = 'A number of OPTL must be selected';
    const ERR_TESTING_FACILITY_TPTL_REQUIRE = 'A number of TPTL must be selected';
    const ERR_TESTING_FACILITY_REQUIRE = 'A number for either OPTL or TPTL must be selected';

    /** @var ContactValidator */
    private $contactValidator;

    public function __construct($errors = null)
    {
        parent::__construct($errors);
        $this->contactValidator = new ContactValidator();
    }

    public function validate(VehicleTestingStationDto $siteDto)
    {
        //  --  Validate contact   --
        /** @var SiteContactDto $contactDto */
        foreach ($siteDto->getContacts() as $contactDto) {
            $this->errors = $this->contactValidator->validate($contactDto);
        }

        $this->validateSiteDetail($siteDto);

        $this->errors->throwIfAnyField();
    }

    private function validateSiteDetail(VehicleTestingStationDto $siteDto)
    {
        if ($this->isEmpty(trim($siteDto->getType())) || SiteTypeCode::exists($siteDto->getType()) === false) {
            $this->errors->add(self::ERR_LOCATION_TYPE_REQUIRE, self::FIELD_LOCATION_TYPE);
        }

        if (empty($siteDto->getTestClasses())) {
            $this->errors->add(self::ERR_VEHICLE_CLASS_REQUIRE, self::FIELD_VEHICLE_CLASS);
        }
        $this->validateFacilities($siteDto);
    }

    private function validateFacilities(VehicleTestingStationDto $siteDto)
    {
        if ($siteDto->isOptlSelected() === false) {
            $this->errors->add(self::ERR_TESTING_FACILITY_OPTL_REQUIRE, self::FIELD_TESTING_FACILITY_OPTL);
        }
        if ($siteDto->isTptlSelected() === false) {
            $this->errors->add(self::ERR_TESTING_FACILITY_TPTL_REQUIRE, self::FIELD_TESTING_FACILITY_TPTL);
        }
        if ($siteDto->isOptlSelected() === true && $siteDto->isTptlSelected() === true
            && empty($siteDto->getFacilities())) {
            $this->errors->add(self::ERR_TESTING_FACILITY_REQUIRE, self::FIELD_TESTING_FACILITY_OPTL);
        }
    }
}
