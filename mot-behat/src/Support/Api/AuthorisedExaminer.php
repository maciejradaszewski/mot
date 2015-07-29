<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Support\Request;
use DvsaCommon\Dto\Common\AuthForAeStatusDto;
use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Contact\EmailDto;
use DvsaCommon\Dto\Contact\PhoneDto;
use DvsaCommon\Dto\Organisation\AuthorisedExaminerAuthorisationDto;
use DvsaCommon\Dto\Organisation\OrganisationContactDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Enum\CompanyTypeCode;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\Enum\PhoneContactTypeCode;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilder;
use DvsaCommon\UrlBuilder\OrganisationUrlBuilder;
use DvsaCommon\Utility\DtoHydrator;

class AuthorisedExaminer extends MotApi
{
    const AE_NAME = 'some ae name';

    public function search($token, $aeNumber)
    {
        return $this->client->request(
            new Request(
                'GET',
                AuthorisedExaminerUrlBuilder::of()->authorisedExaminerByNumber($aeNumber),
                ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token]
            )
        );
    }

    public function getAuthorisedExaminerDetails($token, $userId)
    {
        return $this->client->request(
            new Request(
                'GET',
                AuthorisedExaminerUrlBuilder::of($userId),
                ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token]
            )
        );
    }

    public function createAuthorisedExaminer($token)
    {
        $dto = (new OrganisationDto())
            ->setContacts(
                [
                    (new OrganisationContactDto())
                        ->setType(OrganisationContactTypeCode::REGISTERED_COMPANY)
                        ->setAddress(
                            (new AddressDto())
                                ->setAddressLine1('AddressLine1')
                                ->setTown('Town')
                                ->setPostcode('Postcode')
                        )
                        ->setEmails(
                            [
                                (new EmailDto())
                                    ->setIsSupplied(false)
                                    ->setIsPrimary(true)
                            ]
                        )
                        ->setPhones(
                            [
                                (new PhoneDto())
                                    ->setContactType(PhoneContactTypeCode::BUSINESS)
                                    ->setIsPrimary(true)
                                    ->setNumber('0123456789')
                            ]
                        )
                ]
            )
            ->setName(self::AE_NAME)
            ->setCompanyType(CompanyTypeCode::SOLE_TRADER);

        return $this->client->request(
            new Request(
                'POST',
                'authorised-examiner',
                ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token],
                json_encode(DtoHydrator::dtoToJson($dto))
            )
        );
    }

    public function updateStatusAuthorisedExaminer($token, $id, $status)
    {
        $dto = (new OrganisationDto())
            ->setId($id)
            ->setAuthorisedExaminerAuthorisation(
                (new AuthorisedExaminerAuthorisationDto())
                    ->setStatus(
                        (new AuthForAeStatusDto())
                            ->setCode($status)
                    )
            );

        return $this->client->request(
            new Request(
                'PUT',
                AuthorisedExaminerUrlBuilder::status($id),
                ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token],
                json_encode(DtoHydrator::dtoToJson($dto))
            )
        );
    }

    public function removeAuthorisedExaminer($token)
    {
        return $this->client->request(
            new Request(
                'DELETE',
                OrganisationUrlBuilder::position(2, 4),
                ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token]
            )
        );
    }
}
