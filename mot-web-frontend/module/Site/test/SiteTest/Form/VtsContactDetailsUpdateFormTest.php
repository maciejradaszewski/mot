<?php

namespace SiteTest\Form;

use DvsaClient\ViewModel\EmailFormModel;
use DvsaClient\ViewModel\PhoneFormModel;
use DvsaCommon\Dto\Contact\EmailDto;
use DvsaCommon\Dto\Contact\PhoneDto;
use DvsaCommon\Dto\Site\SiteContactDto;
use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommon\Enum\PhoneContactTypeCode;
use DvsaCommon\Enum\SiteContactTypeCode;
use DvsaCommonTest\TestUtils\TestCaseTrait;
use DvsaCommonTest\TestUtils\XMock;
use Site\Form\VtsContactDetailsUpdateForm;
use Zend\Stdlib\Parameters;

class VtsContactDetailsUpdateFormTest extends \PHPUnit_Framework_TestCase
{
    use TestCaseTrait;

    /**
     * @var VtsContactDetailsUpdateForm
     */
    private $model;
    /**
     * @var EmailFormModel
     */
    private $mockEmailModel;
    /**
     * @var PhoneFormModel
     */
    private $mockPhoneModel;

    public function setUp()
    {
        $this->model = $this->getMockBuilder(VtsContactDetailsUpdateForm::class)
            ->disableOriginalConstructor()
            ->setMethods(['getBusPhoneModel', 'getBusEmailModel'])
            ->getMock();

        $this->mockPhoneModel = XMock::of(PhoneFormModel::class, ['fromPost', 'fromDto', 'toDto', 'isValid']);
        $this->mockEmailModel = XMock::of(EmailFormModel::class, ['fromPost', 'fromDto', 'toDto', 'isValid']);

        $this->mockMethod($this->model, 'getBusEmailModel', $this->any(), $this->mockEmailModel);
        $this->mockMethod($this->model, 'getBusPhoneModel', $this->any(), $this->mockPhoneModel);
    }

    public function tearDown()
    {
        unset($this->model);
    }

    public function test()
    {
        $this->assertTrue(true);
    }

    public function testInit()
    {
        $model = new VtsContactDetailsUpdateForm();

        $actual = $model->getBusPhoneModel();
        $this->assertInstanceOf(PhoneFormModel::class, $actual);
        $this->assertTrue($actual->isPrimary());
        $this->assertEquals(PhoneContactTypeCode::BUSINESS, $actual->getType());

        $actual = $model->getBusEmailModel();
        $this->assertInstanceOf(EmailFormModel::class, $actual);
        $this->assertTrue($actual->isPrimary());
    }

    public function testFromPost()
    {
        $postData = new Parameters();

        //  mocking
        $this->mockMethod($this->mockPhoneModel, 'fromPost', $this->once(), null, [$postData]);
        $this->mockMethod($this->mockEmailModel, 'fromPost', $this->once(), null, [$postData]);

        //  call
        $model = $this->model->fromPost($postData);

        //  check
        $this->assertInstanceOf(VtsContactDetailsUpdateForm::class, $model);
    }

    public function testToDto()
    {
        $emailDto = new EmailDto();
        $phoneDto = new PhoneDto();

        $contactDto = (new SiteContactDto())
            ->setType(SiteContactTypeCode::BUSINESS);

        $vtsDto = (new VehicleTestingStationDto())
            ->setContacts([$contactDto]);

        //  mocking
        $this->mockMethod($this->mockPhoneModel, 'fromDto', $this->once());
        $this->mockMethod($this->mockEmailModel, 'fromDto', $this->once());

        $this->mockMethod($this->mockPhoneModel, 'toDto', $this->once(), $phoneDto);
        $this->mockMethod($this->mockEmailModel, 'toDto', $this->once(), $emailDto);

        //  logical block :: call
        //  init class
        $this->model->fromDto($vtsDto);
        //  call tested method
        $actual = $this->model->toDto();

        //  check
        $this->assertInstanceOf(SiteContactDto::class, $actual);
        $this->assertSame(
            $contactDto
                ->setEmails([$emailDto])
                ->setPhones([$phoneDto]),
            $actual
        );
    }

    public function testFromDto()
    {
        $emailDto = (new EmailDto())->setIsPrimary(true);
        $phoneDto = (new PhoneDto())->setIsPrimary(true);

        $contactDto = (new SiteContactDto())
            ->setType(SiteContactTypeCode::BUSINESS)
            ->setEmails([$emailDto])
            ->setPhones([$phoneDto]);

        $vtsDto = (new VehicleTestingStationDto())
            ->setContacts([$contactDto]);

        //  mocking
        $this->mockMethod($this->mockPhoneModel, 'fromDto', $this->once(), null, $phoneDto);
        $this->mockMethod($this->mockEmailModel, 'fromDto', $this->once(), null, $emailDto);

        //  call
        $model = $this->model->fromDto($vtsDto);

        //  check
        $this->assertInstanceOf(VtsContactDetailsUpdateForm::class, $model);
    }

    public function testGetVtsDto()
    {
        $vtsDto = (new VehicleTestingStationDto());

        //  call
        $actual = $this->model
            ->fromDto($vtsDto)
            ->getVtsDto();

        //  check
        $this->assertSame($vtsDto, $actual);
    }

    /**
     * @dataProvider dataProviderTestIsValid
     */
    public function testIsValid($isEmailValid, $isPhoneValid, $expect)
    {
        //  mocking
        $this->mockMethod($this->mockPhoneModel, 'isValid', $this->once(), $isPhoneValid);
        $this->mockMethod($this->mockEmailModel, 'isValid', $this->once(), $isEmailValid);

        //  call
        $actual = $this->model->isValid();

        //  check
        $this->assertEquals($expect, $actual);

    }

    public function dataProviderTestIsValid()
    {
        return [
            [
                'isEmailValid' => true,
                'isPhoneValid' => true,
                'expect'       => true,
            ],
            [false, true, false],
            [true, false, false],
        ];
    }
}
