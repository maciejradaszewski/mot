<?php

namespace Dvsa\Mot\Behat\Support\Api\Session;

use Dvsa\Mot\Behat\Support\Request;
use DvsaCommon\Dto\Common\AuthForAeStatusDto;
use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Contact\EmailDto;
use DvsaCommon\Dto\Contact\PhoneDto;
use DvsaCommon\Dto\Site\SiteContactDto;
use DvsaCommon\Dto\Site\SiteDto;
use DvsaCommon\Enum\CompanyTypeCode;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\Enum\PhoneContactTypeCode;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilder;
use DvsaCommon\UrlBuilder\OrganisationUrlBuilder;
use DvsaCommon\Utility\DtoHydrator;
use Dvsa\Mot\Behat\Support\Api\MotApi;

class VtsTestLogs extends MotApi
{
    const SITE_NAME = 'some site name';
    const TEST_LOGS = 'authorised-examiner/{authorised_examiner_id}/mot-test-log';

    public function search($token, $siteNumber)
    {
        return $this->client->request(
            new Request(
                'GET',
                AuthorisedExaminerUrlBuilder::of()->authorisedExaminerByNumber($siteNumber),
                ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token]
            )
        );
    }

    public function getAuthorisedExaminerDetails($token, $siteId)
    {
        return $this->client->request(
            new Request(
                'GET',
                AuthorisedExaminerUrlBuilder::of($siteId),
                ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token]
            )
        );
    }

    public function getAuthorisedExaminerPositions($token, $siteId)
    {
        $url = OrganisationUrlBuilder::position($siteId)->toString();

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
        $siteDto = new AuthorisedExaminerAuthorisationDto();
        $siteDto->setAssignedAreaOffice(1);
        $statusDto = new AuthForAeStatusDto();
        $statusDto->setCode("APRVD");
        $siteDto->setStatus($statusDto);

        $dto = (new SiteDto())
            ->setAuthorisedExaminerAuthorisation($siteDto)
            ->setContacts(
                [
                    (new SiteContactDto())
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
            ->setName(self::SITE_NAME)
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

    public function updateStatusAuthorisedExaminer($token, $siteId, $status)
    {
        $siteDto = new AuthorisedExaminerAuthorisationDto();
        $siteDto->setAssignedAreaOffice(1);
        $statusDto = new AuthForAeStatusDto();
        $statusDto->setCode($status);
        $siteDto->setStatus($statusDto);

        $dto = (new SiteDto())
            ->setId($siteId)
            ->setAuthorisedExaminerAuthorisation($siteDto);

        return $this->client->request(
            new Request(
                'PUT',
                AuthorisedExaminerUrlBuilder::status($siteId),
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

    public function getTodaysTestLogs($token, $examinerId)
    {
        $body = json_encode(
            [
                'organisationId' => null,
                'siteNr' => null,
                'personId' => null,
                'vehicleId' => null,
                'vehicleRegNr' => null,
                'vehicleVin' => null,
                'dateFromTs' => strtotime('today 01 am'),
                'dateToTs' => strtotime('tomorrow 01 am'),
                'status' => [
                    0 => 'ABANDONED',
                    1 => 'ABORTED',
                    2 => 'ABORTED_VE',
                    3 => 'FAILED',
                    4 => 'PASSED',
                    5 => 'REFUSED',
                ],
                'testType' => [
                    0 => 'NT',
                    1 => 'PL',
                    2 => 'PV',
                    3 => 'RT',
                ],
                'format' => 'DATA_CSV',
                'isSearchRecent' => false,
                'pageNr' => 1,
                'rowsCount' => 50000,
                'searchTerm' => null,
                'sortBy' => 'testDateTime',
                'sortDirection' => 'DESC',
                'start' => null,
                'filter' => null,
                'isApiGetData' => true,
                'isApiGetTotalCount' => false,
                'isEsEnabled' => null,
                '_class' => 'DvsaCommon\Dto\Search\MotTestSearchParamsDto',
            ]
        );

        return $this->client->request(
            new Request(
                MotApi::METHOD_POST,
                str_replace('{authorised_examiner_id}', $examinerId, self::TEST_LOGS),
                ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
                $body
            )
        );
    }
}
