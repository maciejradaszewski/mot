<?php

namespace OrganisationTest\Form;

use DvsaClient\ViewModel\AddressFormModel;
use DvsaClient\ViewModel\ContactDetailFormModel;
use DvsaClient\ViewModel\EmailFormModel;
use DvsaClient\ViewModel\PhoneFormModel;
use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Organisation\OrganisationContactDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommonTest\TestUtils\TestCaseTrait;
use DvsaCommonTest\TestUtils\XMock;
use Organisation\Form\AeContactDetailsForm;
use Zend\Stdlib\Parameters;

class AeContactDetailsFormTest extends \PHPUnit_Framework_TestCase
{
    use TestCaseTrait;

    /**
     * @var AeContactDetailsForm
     */
    private $model;
    /**
     * @var ContactDetailFormModel
     */
    private $mockCorrModel;

    public function setUp()
    {
        $this->model = $this->getMockBuilder(AeContactDetailsForm::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCorrContactModel'])
            ->getMock();

        $this->mockCorrModel = XMock::of(
            ContactDetailFormModel::class,
            ['fromPost', 'fromDto', 'toDto', 'getEmailModel', 'getPhoneModel', 'getAddressModel']
        );
        $this->mockMethod($this->model, 'getCorrContactModel', $this->any(), $this->mockCorrModel);
    }

    public function tearDown()
    {
        unset($this->model);
    }

    /**
     * @dataProvider dataProviderTestGetSet
     */
    public function testGetSet($property, $value, $expect = null)
    {
        $method = ucfirst($property);

        //  logical block: set value and check set method
        $result = $this->model->{'set' . $method}($value);
        $this->assertInstanceOf(AeContactDetailsForm::class, $result);

        //  logical block: check get method
        $expect = ($expect === null ? $value : $expect);
        $method = (is_bool($expect) ? 'is' : 'get') . $method;
        $this->assertEquals($expect, $this->model->{$method}());
    }

    public function dataProviderTestGetSet()
    {
        return [
            ['cancelUrl', 'test_CancelUrl'],
        ];
    }

    /**
     * @dataProvider dataProviderTestFromPost
     */
    public function testFromPost($postIsAddressTheSame, $expectIsAddressTheSame)
    {
        $postData = (new Parameters())
            ->set(AeContactDetailsForm::FIELD_IS_CORR_ADDR_THE_SAME, $postIsAddressTheSame);

        //  mocking
        $this->mockMethod($this->mockCorrModel, 'fromPost', $this->once(), null, [$postData]);

        //  call
        $model = $this->model->fromPost($postData);

        //  logical block :: check
        $this->assertInstanceOf(AeContactDetailsForm::class, $model);
        $this->assertEquals($expectIsAddressTheSame, $model->isCorrAddressTheSame());
    }

    public function dataProviderTestFromPost()
    {
        return [
            [
                'postIsAddressTheSame'   => 1,
                'expectIsAddressTheSame' => true,
            ],
            [null, false],
        ];
    }

    /**
     * @dataProvider dataProviderTestFromDto
     */
    public function testFromDto(
        OrganisationContactDto $busContact = null,
        OrganisationContactDto $corrContact = null,
        $expectIsSame = null
    ) {
        //  logical block :: check
        $dto = new OrganisationDto();
        $dto->setContacts(array_filter([$busContact, $corrContact]));

        $model = new AeContactDetailsForm($dto);    //  constructor call fromDto method

        //  logical block :: check
        $this->assertEquals($expectIsSame, $model->isCorrAddressTheSame());

        //  check correspondence details model
        $corrContact = ($expectIsSame === true ? $busContact : $corrContact);
        $expectCorrModel = (new ContactDetailFormModel(OrganisationContactTypeCode::CORRESPONDENCE))
            ->fromDto($corrContact);
        $this->assertEquals($expectCorrModel, $model->getCorrContactModel());
    }

    public function dataProviderTestFromDto()
    {
        $addressDto = (new AddressDto())
            ->setAddressLine4('test_addrLine1');

        $busContactDto = new OrganisationContactDto();
        $busContactDto
            ->setType(OrganisationContactTypeCode::REGISTERED_COMPANY)
            ->setAddress($addressDto);

        $corrContactDto = clone $busContactDto;
        $corrContactDto->setType(OrganisationContactTypeCode::CORRESPONDENCE);

        return [
            [
                'busContact'   => null,
                'corrContact'  => null,
                'expectIsSame' => true,
            ],
            //  check when corr details is null (address will be empty object)
            [
                'busContact'   => $busContactDto,
                'corrContact'  => null,
                'expectIsSame' => true,
            ],
            //  check when corr and bus contacts the same
            [
                'busContact'   => $busContactDto,
                'corrContact'  => $corrContactDto,
                'expectIsSame' => true,
            ],
            //  check when corr details not null, but address is null
            [
                'busContact'   => $busContactDto,
                'corrContact'  => $this->cloneObj($corrContactDto)->setAddress(null),
                'expectIsSame' => true,
            ],
            //  check when corr details not null, but address not same
            [
                'busContact'   => $busContactDto,
                'corrContact'  => $this->cloneObj($corrContactDto)
                    ->setAddress((new AddressDto())->setTown('test_Town1')),
                'expectIsSame' => false,
            ],
        ];
    }


    /**
     * @dataProvider dataProviderTestToDto
     */
    public function testToDto($isCorrAddrTheSame)
    {
        $expectContact = XMock::of(OrganisationContactDto::class, ['setAddress']);
        $this->mockMethod($expectContact, 'setAddress', $this->isCall($isCorrAddrTheSame));

        //  logical block :: mocking
        $this->mockMethod($this->mockCorrModel, 'toDto', $this->once(), $expectContact);
        $this->mockMethod($this->mockCorrModel, 'getAddressModel', $this->once(), new AddressFormModel());

        //  logical block :: call
        $actual = $this->model
            ->fromDto(new OrganisationDto())
            ->fromPost((new Parameters())->set(AeContactDetailsForm::FIELD_IS_CORR_ADDR_THE_SAME, $isCorrAddrTheSame))
            ->toDto();

        //  logical block :: check
        $this->assertInstanceOf(OrganisationDto::class, $actual);
        $this->assertEquals([$expectContact], $actual->getContacts());
    }

    public function dataProviderTestToDto()
    {
        return [
            [
                'isCorrAddrTheSame' => true,
            ],
            [false],
        ];
    }

    /**
     * @dataProvider dataProviderTestIsValid
     */
    public function testIsValid($isAddrTheSame, $isEmailValid, $isPhoneValid, $isAddressValid, $expect)
    {
        //  mocking
        $mockEmailModel = XMock::of(EmailFormModel::class, ['isValid']);
        $this->mockMethod($mockEmailModel, 'isValid', $this->once(), $isEmailValid);

        $mockPhoneModel = XMock::of(PhoneFormModel::class, ['isValid']);
        $this->mockMethod($mockPhoneModel, 'isValid', $this->once(), $isPhoneValid);

        $mockAddressModel = XMock::of(AddressFormModel::class, ['isValid']);
        $this->mockMethod($mockAddressModel, 'isValid', $this->isCall(!$isAddrTheSame), $isAddressValid);

        $this->mockMethod($this->mockCorrModel, 'getPhoneModel', $this->once(), $mockPhoneModel);
        $this->mockMethod($this->mockCorrModel, 'getEmailModel', $this->once(), $mockEmailModel);
        $this->mockMethod($this->mockCorrModel, 'getAddressModel', $this->isCall(!$isAddrTheSame), $mockAddressModel);

        $actual = $this->model
            ->fromPost((new Parameters())->set(AeContactDetailsForm::FIELD_IS_CORR_ADDR_THE_SAME, $isAddrTheSame))
            ->isValid();

        $this->assertEquals($expect, $actual);
    }

    public function dataProviderTestIsValid()
    {
        return [
            [
                'isAddrTheSame'  => 1,
                'isEmailValid'   => true,
                'isPhoneValid'   => true,
                'isAddressValid' => null,
                'expect'         => true,
            ],
            [1, false, true, null, false],
            [0, true, true, true, true],
            [0, true, true, false, false],
        ];
    }

    private function isCall($isExpectCall)
    {
        return ($isExpectCall ? $this->once() : $this->never());
    }

    /**
     * @return OrganisationDto|OrganisationContactDto
     */
    private function cloneObj($obj)
    {
        return clone $obj;
    }
}
