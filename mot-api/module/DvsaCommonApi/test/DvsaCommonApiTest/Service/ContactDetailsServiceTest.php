<?php

namespace DvsaCommonApiTest\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Contact\ContactDto;
use DvsaCommon\Dto\Contact\EmailDto;
use DvsaCommon\Dto\Contact\PhoneDto;
use DvsaCommon\Dto\Organisation\OrganisationContactDto;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\Enum\PhoneContactTypeCode;
use DvsaCommonApi\Service\AddressService;
use DvsaCommonApi\Service\ContactDetailsService;
use DvsaCommonApi\Service\Validator\ContactDetailsValidator;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\Address;
use DvsaEntities\Entity\ContactDetail;
use DvsaEntities\Entity\Email;
use DvsaEntities\Entity\Phone;
use DvsaEntities\Entity\PhoneContactType;
use DvsaEntities\Repository\PhoneContactTypeRepository;
use PHPUnit_Framework_MockObject_MockObject as MockObj;

class ContactDetailsServiceTest extends AbstractServiceTest
{
    /** @var  EntityManager|MockObj */
    private $mockEntityManager;
    /** @var  AddressService|MockObj */
    private $mockAddressSrv;
    /** @var  PhoneContactTypeRepository|MockObj */
    private $mockPhoneContactRepo;
    /** @var  ContactDetailsValidator|MockObj */
    private $mockContactDetailValidator;
    /** @var  ContactDetailsService|MockObj */
    private $service;

    public function setUp()
    {
        $this->mockEntityManager = XMock::of(EntityManager::class);
        $this->mockAddressSrv = XMock::of(AddressService::class);//, ['persist']);
        $this->mockPhoneContactRepo = XMock::of(PhoneContactTypeRepository::class);
        $this->mockContactDetailValidator = XMock::of(ContactDetailsValidator::class);

        $this->service = new ContactDetailsService(
            $this->mockEntityManager,
            $this->mockAddressSrv,
            $this->mockPhoneContactRepo,
            $this->mockContactDetailValidator
        );
    }

    public function tearDown()
    {
        unset(
            $this->mockEntityManager,
            $this->mockAddressSrv,
            $this->mockPhoneContactRepo,
            $this->mockContactDetailValidator,
            $this->service
        );
    }

    /**
     * @param ContactDetail $detailsEntity
     * @param ContactDto    $dto
     * @param Address       $expectAddress
     *
     * @dataProvider dataProviderTestUpdateAddressInContactDetails
     */
    public function testUpdateAddressInContactDetails(
        $detailsEntity,
        ContactDto $dto,
        $expectAddress
    ) {
        if ($dto->getAddress() instanceof AddressDto && $dto->getAddress()->isEmpty()) {
            $this->mockMethod(
                $this->mockEntityManager, 'remove', $this->once(), null, [$expectAddress]
            );
        } elseif ($expectAddress instanceof Address) {
            $this->mockMethod(
                $this->mockAddressSrv,
                'persist',
                $this->once(),
                null,
                [$expectAddress, $dto->getAddress()->toArray(), true]
            );
        }

        $this->mockMethod($this->mockEntityManager, 'persist', $this->once());

        $actualDetailsEntity = ($detailsEntity instanceof ContactDetail ? $detailsEntity : new ContactDetail);
        $actualDetailsEntity = $this->service->setContactDetailsFromDto($dto, $actualDetailsEntity);

        if ($dto->getAddress() instanceof AddressDto && $dto->getAddress()->isEmpty()) {
            $this->assertNull($actualDetailsEntity->getAddress());
        }

        if ($detailsEntity instanceof ContactDetail) {
            $this->assertEquals($detailsEntity->getAddress(), $actualDetailsEntity->getAddress());
        }
    }

    public function dataProviderTestUpdateAddressInContactDetails()
    {
        //  --  dto and result address  --
        $addressLine1 = 'unit address line1 ';
        $town = 'unit town';

        $addressDto = (new AddressDto())
            ->setAddressLine1($addressLine1)
            ->setTown($town);

        $contactDto = new ContactDto();
        $contactDto->setAddress($addressDto);

        //  --  details entity  --
        $addressEntity = (new Address)
            ->setAddressLine1('unit address exists')
            ->setTown('unt town exists');

        $detailsEntity = new ContactDetail();
        $detailsEntity->setAddress($addressEntity);

        return [
            [
                'entity'           => null,
                'dto'              => $contactDto,
                'expectSetAddress' => new Address,
            ],
            [$detailsEntity, $contactDto, $addressEntity],
            [
                $detailsEntity,
                (new OrganisationContactDto())
                    ->setAddress($addressDto)
                    ->setType(OrganisationContactTypeCode::REGISTERED_COMPANY),
                $addressEntity,
            ],
            //  --  check address not changed if address dto not provided   --
            [$detailsEntity, (new ContactDto())->setAddress(null), null],
            //  --  check if address dto is empty, then remove address record from details  --
            //  this condition should be last, because function remove address from $detailsEntity
            [$detailsEntity, (new ContactDto())->setAddress(new AddressDto()), $addressEntity],
        ];
    }

    /**
     * @param Phone    $detailsEntity
     * @param PhoneDto $dto
     * @param string   $typeCode
     * @param Phone    $expectEntity
     * @param Phone    $expectRemoveEntity
     *
     * @dataProvider dataProviderTestUpdatePhonesInContactDetails
     */
    public function testUpdatePhonesInContactDetails($entity, $dto, $typeCode, $expectEntity, $expectRemoveEntity)
    {
        $detailsEntity = new ContactDetail();
        if ($entity) {
            $detailsEntity->addPhone($entity);
        }

        if ($expectRemoveEntity instanceof Phone) {
            $this->mockMethod($this->mockEntityManager, 'remove', $this->once(), null, [$expectRemoveEntity]);
        } else {
            $expectEntity->setContact($detailsEntity);

            $this->mockMethod(
                $this->mockPhoneContactRepo, 'getByCode', $this->once(),
                (new PhoneContactType())->setCode($typeCode)
            );
        }

        $this->mockMethod($this->mockEntityManager, 'persist', $this->once());

        //  --  call    --
        $contactDto = new ContactDto();
        $contactDto->setPhones([$dto]);

        $actualDetailsEntity = $this->service->setContactDetailsFromDto(
            $contactDto,
            ($detailsEntity instanceof ContactDetail ? $detailsEntity : new ContactDetail)
        );

        $this->assertEquals(
            ($expectEntity === null ? [] : [$expectEntity]),
            $actualDetailsEntity->getPhones()->toArray()
        );
    }

    public function dataProviderTestUpdatePhonesInContactDetails()
    {
        $number = '1234567890';

        //  --  dto --
        $phoneDto = (new PhoneDto())
            ->setNumber($number)
            ->setIsPrimary(true)
            ->setContactType(PhoneContactTypeCode::BUSINESS);

        //  --  entity  --
        $phoneEntity = (new Phone)
            ->setNumber($number)
            ->setIsPrimary(true)
            ->setContactType((new PhoneContactType())->setCode(PhoneContactTypeCode::BUSINESS));

        $phoneEntity2 = $this->clonePhone($phoneEntity)->setId(999);

        return [
            //  --  contact has not phones, dto has -> should add phone to contact  --
            [
                'entity'             => null,
                'dto'                => $this->clonePhoneDto($phoneDto),
                'typeCode'           => PhoneContactTypeCode::BUSINESS,
                'expectEntity'        => $this->clonePhone($phoneEntity),
                'expectRemoveEntity' => null,
            ],
            //  --  contact has phones, dto has number -> should update phone at contact  --
            [
                'entity'             => $this->clonePhone($phoneEntity2),
                'dto'                => $this->clonePhoneDto($phoneDto)->setId(999)->setNumber('+A99881'),
                'typeCode'           => PhoneContactTypeCode::BUSINESS,
                'expectEntity'        => $this->clonePhone($phoneEntity2)->setNumber('+A99881'),
                'expectRemoveEntity' => null,
            ],
            //  --  contact has phones, dto has number -> should find primary nr and update at contact  --
            [
                'entity'             => $this->clonePhone($phoneEntity),
                'dto'                => $this->clonePhoneDto($phoneDto)->setNumber('+A99881'),
                'typeCode'           => PhoneContactTypeCode::BUSINESS,
                'expectEntity'        => $this->clonePhone($phoneEntity)->setNumber('+A99881'),
                'expectRemoveEntity' => null,
            ],
            //  --  details has phone, dto without number -> phone should be removed from contact    --
            [
                'entity'             => $phoneEntity2,
                'dto'                => $this->clonePhoneDto($phoneDto)->setNumber(''),
                'typeCode'           => null,
                'expectEntity'        => null,
                'expectRemoveEntity' => $phoneEntity2,
            ],
        ];
    }



    /**
     * @param Email    $detailsEntity
     * @param EmailDto $emailDto
     * @param Email    $expect
     * @param Email    $expectRemoveEntity
     *
     * @dataProvider dataProviderTestUpdateEmailsInContactDetails
     */
    public function testUpdateEmailsInContactDetails($emailEntity, $emailDto, $expect, $expectRemoveEntity)
    {
        $detailsEntity = new ContactDetail();
        if ($emailEntity) {
            $detailsEntity->addEmail($emailEntity);
        }

        if ($expectRemoveEntity instanceof Email) {
            $this->mockMethod($this->mockEntityManager, 'remove', $this->once(), null, [$expectRemoveEntity]);
        } else {
            $expect->setContact($detailsEntity);
        }

        $this->mockMethod($this->mockEntityManager, 'persist', $this->once());

        //  --  call    --
        $contactDto = new ContactDto();
        $contactDto->setEmails([$emailDto]);

        $actualDetailsEntity = $this->service->setContactDetailsFromDto(
            $contactDto,
            ($detailsEntity instanceof ContactDetail ? $detailsEntity : new ContactDetail)
        );

        $this->assertEquals(
            ($expect === null ? [] : [$expect]),
            $actualDetailsEntity->getEmails()->toArray()
        );
    }

    public function dataProviderTestUpdateEmailsInContactDetails()
    {
        $email = 'unittest@test.com';

        //  --  dto --
        $emailDto = (new EmailDto())
            ->setEmail($email)
            ->setIsPrimary(true);

        //  --  entity  --
        $emailEntity = (new Email())
            ->setEmail($email)
            ->setIsPrimary(true);

        $emailEntity2 = $this->cloneEmail($emailEntity)->setId(999);

        return [
            //  --  contact has not emails, dto has -> should add email to contact  --
            [
                'entity'             => null,
                'dto'                => $this->cloneEmailDto($emailDto),
                'expect'             => $this->cloneEmail($emailEntity),
                'expectRemoveEntity' => null,
            ],
            //  --  contact has email, dto has address -> should update email by Id at contact  --
            [
                'entity'             => $this->cloneEmail($emailEntity2),
                'dto'                => $this->cloneEmailDto($emailDto)->setId(999)->setEmail('aaa@bbb.com'),
                'expect'             => $this->cloneEmail($emailEntity2)->setEmail('aaa@bbb.com'),
                'expectRemoveEntity' => null,
            ],
            //  --  contact has email, dto has address -> should find primary email and update at contact  --
            [
                'entity'             => $this->cloneEmail($emailEntity),
                'dto'                => $this->cloneEmailDto($emailDto)->setEmail('bbb@cccc.com'),
                'expect'             => $this->cloneEmail($emailEntity)->setEmail('bbb@cccc.com'),
                'expectRemoveEntity' => null,
            ],
            //  --  details has email, dto without address -> email should be removed from contact    --
            [
                'entity'             => $emailEntity2,
                'dto'                => $this->cloneEmailDto($emailDto)->setEmail(''),
                'expect'             => null,
                'expectRemoveEntity' => $emailEntity2,
            ],
        ];
    }

    /** @return PhoneDto */
    private function clonePhoneDto($dto)
    {
        return clone $dto;
    }

    /** @return Phone */
    private function clonePhone($entity)
    {
        return clone $entity;
    }

    /** @return EmailDto */
    private function cloneEmailDto($dto)
    {
        return clone $dto;
    }

    /** @return Email */
    private function cloneEmail($entity)
    {
        return clone $entity;
    }
}
