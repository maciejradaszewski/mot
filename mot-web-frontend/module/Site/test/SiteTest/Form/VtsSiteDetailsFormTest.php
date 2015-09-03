<?php

namespace SiteTest\Form;


use DvsaClient\ViewModel\AddressFormModel;
use DvsaClient\ViewModel\ContactDetailFormModel;
use DvsaClient\ViewModel\EmailFormModel;
use DvsaClient\ViewModel\PhoneFormModel;
use DvsaCommon\Constants\FacilityTypeCode;
use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Contact\EmailDto;
use DvsaCommon\Dto\Contact\PhoneDto;
use DvsaCommon\Dto\Site\FacilityDto;
use DvsaCommon\Dto\Site\FacilityTypeDto;
use DvsaCommon\Dto\Site\SiteContactDto;
use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommon\Enum\PhoneContactTypeCode;
use DvsaCommon\Enum\SiteContactTypeCode;
use DvsaCommon\Enum\SiteStatusCode;
use DvsaCommon\Enum\SiteTypeCode;
use DvsaCommon\Enum\SiteTypeName;
use DvsaCommonTest\TestUtils\TestCaseTrait;
use DvsaCommonTest\TestUtils\XMock;
use \PHPUnit_Framework_MockObject_MockObject as MockObject;
use Site\Form\VtsSiteDetailsForm;
use Zend\Stdlib\Parameters;

class VtsSiteDetailsFormTest extends \PHPUnit_Framework_TestCase
{
    use TestCaseTrait;

    const VTS_ID = 1;

    /** @var VtsSiteDetailsForm */
    private $model;

    public function setUp()
    {
        $this->model = new VtsSiteDetailsForm();
    }

    /**
     * @param string $property
     * @param mixed  $value
     * @param mixed  $expect
     *
     * @dataProvider dataProviderTestGetSet
     */
    public function testGetSet($property, $value, $expect = null)
    {
        $method = ucfirst($property);

        //  logical block: set value and check set method
        $result = $this->model->{'set' . $method}($value);
        $this->assertInstanceOf(VtsSiteDetailsForm::class, $result);

        //  logical block: check get method
        $expect = ($expect === null ? $value : $expect);
        $method = (is_bool($expect) ? 'is' : 'get') . $method;
        $this->assertEquals($expect, $this->model->{$method}());
    }

    public function dataProviderTestGetSet()
    {
        return [
            [VtsSiteDetailsForm::FIELD_NAME, 'test_Name'],
            [VtsSiteDetailsForm::FIELD_STATUS,  SiteStatusCode::APPROVED],
            [VtsSiteDetailsForm::FIELD_VEHICLE_CLASS, ["1", "2"]],
        ];
    }

    /**
     * @dataProvider dataProviderTestFromPost
     */
    public function testFromPost($postData)
    {
        $postData = new Parameters($postData);

        //  call
        $model = $this->model->fromPost($postData);

        //  logical block :: check
        //  check type of instances
        $this->assertInstanceOf(VtsSiteDetailsForm::class, $model);

        //  check main fields
        $this->assertEquals($postData->get(VtsSiteDetailsForm::FIELD_NAME), $model->getName());
        $this->assertEquals($postData->get(VtsSiteDetailsForm::FIELD_STATUS), $model->getStatus());
        $this->assertEquals($postData->get(VtsSiteDetailsForm::FIELD_VEHICLE_CLASS), $model->getClasses());
    }

    public function dataProviderTestFromPost()
    {
        return [
            [
                'postData' => [
                    VtsSiteDetailsForm::FIELD_NAME => 'test_SiteName',
                    VtsSiteDetailsForm::FIELD_STATUS => SiteStatusCode::APPROVED,
                    VtsSiteDetailsForm::FIELD_VEHICLE_CLASS => ["1", "2"],
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataProviderTestToDto
     */
    public function testToDto($postData, $expect)
    {
        $actual = $this->model
            ->fromPost(new Parameters($postData))
            ->toDto();

        $this->assertEquals($expect, $actual);
    }


    public function dataProviderTestToDto()
    {
        return [
            [
                'postData' => [
                    VtsSiteDetailsForm::FIELD_NAME => 'test_SiteName',
                    VtsSiteDetailsForm::FIELD_STATUS => SiteStatusCode::APPROVED,
                    VtsSiteDetailsForm::FIELD_VEHICLE_CLASS => ["1", "2"],
                ],
                'expect'   => (new VehicleTestingStationDto())
                    ->setName('test_SiteName')
                    ->setStatus(SiteStatusCode::APPROVED)
                    ->setTestClasses(["1", "2"])
            ],
        ];
    }

    public function testGetDropDown()
    {
        $this->assertSame(
             [
                'AV' => 'Approved',
                'AP' =>'Applied',
                'RE' =>'Retracted',
                'RJ' =>'Rejected',
                'LA' => 'Lapsed',
                'EX' =>'Extinct',
            ],
            $this->model->getStatuses()
        );
    }
}