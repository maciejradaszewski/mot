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
    const POSITION = '/organisation/{organisation_id}/position';
    const POSITION_DELETE = '/organisation/{organisation_id}/position/{position_id}';
    
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

    public function getAuthorisedExaminerDetails($token, $aeId)
    {
        return $this->client->request(
            new Request(
                'GET',
                AuthorisedExaminerUrlBuilder::of($aeId),
                ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token]
            )
        );
    }

    public function getAuthorisedExaminerPositions($token, $aeId)
    {
        $url = OrganisationUrlBuilder::position($aeId)->toString();

        return $this->client->request(
            new Request(
                'GET',
                $url,
                ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token]
            )
        );
    }

    public function createAuthorisedExaminer($token)
    {
        $aeDto = new AuthorisedExaminerAuthorisationDto();
        $aeDto->setAssignedAreaOffice(1);
        $statusDto = new AuthForAeStatusDto();
        $statusDto->setCode("APRVD");
        $aeDto->setStatus($statusDto);

        $dto = (new OrganisationDto())
            ->setAuthorisedExaminerAuthorisation($aeDto)
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
        $aeDto = new AuthorisedExaminerAuthorisationDto();
        $aeDto->setAssignedAreaOffice(1);
        $statusDto = new AuthForAeStatusDto();
        $statusDto->setCode($status);
        $aeDto->setStatus($statusDto);

        $dto = (new OrganisationDto())
            ->setId($id)
            ->setAuthorisedExaminerAuthorisation($aeDto);

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

    public function nominate($userId, $orgRoleName, $orgId, $token)
    {
        $roles = [
            "Authorised examiner designated manager" => 1,
            "Authorised examiner delegate" => 2
        ];

        if (!array_key_exists($orgRoleName, $roles)) {
            throw new \InvalidArgumentException("Organisation role '" .$orgRoleName. "' not found");
        }

        $orgRoleId = $roles[$orgRoleName];
        $data = [
            "nomineeId" => $userId,
            "roleId" => $orgRoleId
        ];

        return $this->sendRequest(
            $token,
            MotApi::METHOD_POST,
            OrganisationUrlBuilder::position($orgId),
            $data
        );
    }

    public function linkAuthorisedExaminerWithSite($token, $aeId, $siteName)
    {
        $linkUrl = AuthorisedExaminerUrlBuilder::siteLink($aeId);
        $request = new Request(
            'POST',
            $linkUrl,
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token],
            json_encode(['siteNumber' => $siteName])
        );
        return $this->client->request($request);
    }

    public function denominate($orgId, $positionId, $token)
    {
        $url = self::POSITION_DELETE;
        $url = str_replace("{organisation_id}", $orgId, $url);
        $url = str_replace("{position_id}", $positionId, $url);

        return $this->sendRequest(
            $token,
            MotApi::METHOD_DELETE,
            $url
        );
    }

}
