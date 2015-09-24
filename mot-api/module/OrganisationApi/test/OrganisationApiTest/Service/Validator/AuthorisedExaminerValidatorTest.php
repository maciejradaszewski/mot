<?php

namespace OrganisationApiTest\Service\Validator;

use DvsaCommon\Dto\Common\AuthForAeStatusDto;
use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Contact\EmailDto;
use DvsaCommon\Dto\Contact\PhoneDto;
use DvsaCommon\Dto\Organisation\AuthorisedExaminerAuthorisationDto;
use DvsaCommon\Dto\Organisation\OrganisationContactDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Enum\AuthorisationForAuthorisedExaminerStatusCode;
use DvsaCommon\Enum\CompanyTypeCode;
use DvsaCommonApi\Service\Exception\BadRequestException;
use OrganisationApi\Service\Validator\AuthorisedExaminerValidator;

/**
 * Class AuthorisedExaminerValidatorTest
 *
 * @package OrganisationApiTest\Service\Validator
 */
class AuthorisedExaminerValidatorTest extends \PHPUnit_Framework_TestCase
{
    const AE_NAME = 'AE Name';

    /** @var AuthorisedExaminerValidator */
    private $validator;

    public function setUp()
    {
        $this->validator = new AuthorisedExaminerValidator();
    }

    /**
     * @dataProvider dataProviderTestValidator
     */
    public function testValidator($organisation, $errors = false)
    {
        if ($errors === true) {
            $this->setExpectedException(BadRequestException::class, 'Validation errors encountered');
        }

        $this->validator->validate($organisation);
    }

    public function dataProviderTestValidator()
    {
        return [
            // no errors
            [
                'organisation' => (new OrganisationDto())
                    ->setContacts(
                        [
                            (new OrganisationContactDto())
                                ->setAddress(
                                    (new AddressDto())
                                        ->setAddressLine1('AddressLine1')
                                        ->setTown('Town')
                                        ->setPostcode('Postcode')
                                )
                                ->setEmails([(new EmailDto())->setIsPrimary(true)->setEmail('dummy@dummy.com')])
                                ->setPhones([(new PhoneDto())->setIsPrimary(true)->setNumber('0123456789')])
                        ]
                    )
                    ->setName(self::AE_NAME)
                    ->setCompanyType(CompanyTypeCode::SOLE_TRADER),
            ],
            // Valid No Email
            [
                'organisation' => (new OrganisationDto())
                    ->setContacts(
                        [
                            (new OrganisationContactDto())
                                ->setAddress(
                                    (new AddressDto())
                                        ->setAddressLine1('AddressLine1')
                                        ->setTown('Town')
                                        ->setPostcode('Postcode')
                                )
                                ->setEmails([(new EmailDto())->setIsSupplied(false)->setIsPrimary(true)])
                                ->setPhones([(new PhoneDto())->setIsPrimary(true)->setNumber('0123456789')])
                        ]
                    )
                    ->setName(self::AE_NAME)
                    ->setCompanyType(CompanyTypeCode::SOLE_TRADER),
            ],
            // Error no Name
            [
                'organisation' => (new OrganisationDto())
                    ->setContacts(
                        [
                            (new OrganisationContactDto())
                                ->setAddress(
                                    (new AddressDto())
                                        ->setAddressLine1('AddressLine1')
                                        ->setTown('Town')
                                        ->setPostcode('Postcode')
                                )
                                ->setEmails([(new EmailDto())->setIsPrimary(true)->setEmail('dummy@dummy.com')])
                                ->setPhones([(new PhoneDto())->setIsPrimary(true)->setNumber('0123456789')])
                        ]
                    )
                    ->setCompanyType(CompanyTypeCode::SOLE_TRADER),
                'errors' => true,
            ],
            // Error no Type
            [
                'organisation' => (new OrganisationDto())
                    ->setContacts(
                        [
                            (new OrganisationContactDto())
                                ->setAddress(
                                    (new AddressDto())
                                        ->setAddressLine1('AddressLine1')
                                        ->setTown('Town')
                                        ->setPostcode('Postcode')
                                )
                                ->setEmails([(new EmailDto())->setIsPrimary(true)->setEmail('dummy@dummy.com')])
                                ->setPhones([(new PhoneDto())->setIsPrimary(true)->setNumber('0123456789')])
                        ]
                    )
                    ->setName(self::AE_NAME),
                'errors' => true,
            ],
            // Error no Address
            [
                'organisation' => (new OrganisationDto())
                    ->setContacts(
                        [
                            (new OrganisationContactDto())
                                ->setAddress((new AddressDto()))
                                ->setEmails([(new EmailDto())->setIsPrimary(true)->setEmail('dummy@dummy.com')])
                                ->setPhones([(new PhoneDto())->setIsPrimary(true)->setNumber('0123456789')])
                        ]
                    )
                    ->setName(self::AE_NAME)
                    ->setCompanyType(CompanyTypeCode::SOLE_TRADER),
                'errors' => true,
            ],
            // Error no Telephone
            [
                'organisation' => (new OrganisationDto())
                    ->setContacts(
                        [
                            (new OrganisationContactDto())
                                ->setAddress(
                                    (new AddressDto())
                                        ->setAddressLine1('AddressLine1')
                                        ->setTown('Town')
                                        ->setPostcode('Postcode')
                                )
                                ->setEmails([(new EmailDto())->setIsPrimary(true)->setEmail('dummy@dummy.com')])
                        ]
                    )
                    ->setName(self::AE_NAME)
                    ->setCompanyType(CompanyTypeCode::SOLE_TRADER),
                'errors' => true,
            ],
            // Error email invalid
            [
                'organisation' => (new OrganisationDto())
                    ->setContacts(
                        [
                            (new OrganisationContactDto())
                                ->setAddress(
                                    (new AddressDto())
                                        ->setAddressLine1('AddressLine1')
                                        ->setTown('Town')
                                        ->setPostcode('Postcode')
                                )
                                ->setEmails([(new EmailDto())->setIsPrimary(true)->setIsSupplied(true)->setEmail('invalidEmail')])
                                ->setPhones([(new PhoneDto())->setIsPrimary(true)->setNumber('0123456789')])
                        ]
                    )
                    ->setName(self::AE_NAME)
                    ->setCompanyType(CompanyTypeCode::SOLE_TRADER),
                'errors' => true,
            ],
            // Error Company Registration Number Required
            [
                'organisation' => (new OrganisationDto())
                    ->setContacts(
                        [
                            (new OrganisationContactDto())
                                ->setAddress(
                                    (new AddressDto())
                                        ->setAddressLine1('AddressLine1')
                                        ->setTown('Town')
                                        ->setPostcode('Postcode')
                                )
                                ->setEmails([(new EmailDto())->setIsPrimary(true)->setEmail('invalidEmail')])
                                ->setPhones([(new PhoneDto())->setIsPrimary(true)->setNumber('0123456789')])
                        ]
                    )
                    ->setName(self::AE_NAME)
                    ->setCompanyType(CompanyTypeCode::COMPANY)
                    ->setRegisteredCompanyNumber(''),
                'errors' => true,
            ],
        ];
    }

    /**
     * @dataProvider dataProviderTestStatusValidator
     */
    public function testStatusValidator($ae, $errors = false)
    {
        if ($errors === true) {
            $this->setExpectedException(BadRequestException::class, 'Validation errors encountered');
        }

        $this->validator->validateStatus($ae);
    }

    public function dataProviderTestStatusValidator()
    {
        return [
            // no errors
            [
                'ae' => (new AuthorisedExaminerAuthorisationDto())
                    ->setStatus(
                        (new AuthForAeStatusDto())
                            ->setCode(AuthorisationForAuthorisedExaminerStatusCode::APPROVED)
                    ),
            ],
            // no data
            [
                'ae' => (new AuthorisedExaminerAuthorisationDto()),
                'errors' => true,
            ],
            // empty status
            [
                'ae' => (new AuthorisedExaminerAuthorisationDto())
                    ->setStatus(new AuthForAeStatusDto()),
                'errors' => true,
            ],
            // invalid status
            [
                'ae' => (new AuthorisedExaminerAuthorisationDto())
                    ->setStatus(
                        (new AuthForAeStatusDto())
                            ->setCode('invalid')
                    ),
                'errors' => true,
            ],
        ];
    }
}
