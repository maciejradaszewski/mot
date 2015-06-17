<?php

namespace SiteApiTest\Service\Validator;

use DvsaCommon\Enum\VehicleClassCode;
use DvsaCommonApi\Service\Exception\BadRequestException;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use SiteApi\Service\Validator\SiteValidator;

/**
 * Testing that validator returns correct results.
 */
class SiteValidatorTest extends AbstractServiceTestCase
{
    /** @var $siteValidator SiteValidator */
    private $siteValidator;

    public function setup()
    {
        $this->siteValidator = new SiteValidator();
    }

    public function testValidateSiteOk()
    {
        $data = ['name' => 'Test Garage'];
        $this->siteValidator->validate($data);
    }

    public function testValidateFacilitiesWithValidData()
    {
        $data = $this->getValidTestData();
        try {
            $this->siteValidator->validateFacilities($data);
        } catch (BadRequestException $ex) {
            $this->fail("Exception not expected. " . print_r($ex->getErrors()));
        }
        $this->assertTrue(true);
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\BadRequestException
     */
    public function testValidateFacilitiesWithInvalidData()
    {
        $data = $this->getInvalidTestData();
        $this->siteValidator->validateFacilities($data);
    }

    public function testValidateFacilitiesWith4And7Data()
    {
        $data = $this->get4And7TestData();
        $this->siteValidator->validateFacilities($data);
        $this->assertTrue(true);
    }

    private function getValidTestData()
    {
        return
            [
                'roles'      => [0 => VehicleClassCode::CLASS_1,
                                 1 => VehicleClassCode::CLASS_2],
                'facilities' => ['TPTL' => '']
            ];
    }

    private function getInvalidTestData()
    {
        return
            [
                'roles'      => [0 => VehicleClassCode::CLASS_1,
                                 1 => VehicleClassCode::CLASS_2],
                'facilities' => ['TPTL' => '',
                                 'ATL'  => '']
            ];
    }

    private function get4And7TestData()
    {
        return
            [
                'roles'      => [0 => VehicleClassCode::CLASS_4,
                                 1 => VehicleClassCode::CLASS_7],
                'facilities' => ['TPTL' => '',
                                 'ATL'  => '',
                                 'OPTL' => '']
            ];
    }
}
