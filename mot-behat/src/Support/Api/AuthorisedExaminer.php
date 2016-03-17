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
use DvsaCommon\Enum\OrganisationSiteStatusCode;
use DvsaCommon\Enum\PhoneContactTypeCode;
use DvsaCommon\Model\AuthorisedExaminerPatchModel;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilder;
use DvsaCommon\UrlBuilder\OrganisationUrlBuilder;
use DvsaCommon\Utility\DtoHydrator;

class AuthorisedExaminer extends MotApi
{
    const AE_NAME = 'some ae name';
    const POSITION = '/organisation/{organisation_id}/position';
    const POSITION_DELETE = '/organisation/{organisation_id}/position/{position_id}';
    const TEST_LOGS = 'authorised-examiner/{authorised_examiner_id}/mot-test-log';

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
        return $this->client->request(
            new Request(
                'PATCH',
                AuthorisedExaminerUrlBuilder::of($id),
                ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token],
                json_encode([AuthorisedExaminerPatchModel::STATUS => $status])
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

    public function linkAuthorisedExaminerWithSite($token, $aeId, $siteNumber)
    {
        $linkUrl = AuthorisedExaminerUrlBuilder::siteLink($aeId);
        $request = new Request(
            'POST',
            $linkUrl,
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token],
            json_encode(['siteNumber' => $siteNumber])
        );
        return $this->client->request($request);
    }

    public function unlinkSiteFromAuthorisedExaminer($token, $aeId, $linkId)
    {
        $unlinkUrl = AuthorisedExaminerUrlBuilder::siteLink($aeId, $linkId);
        $request = new Request(
            'PUT',
            $unlinkUrl,
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token],
            //todo add const: status: surrender
            json_encode(OrganisationSiteStatusCode::SURRENDERED)
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

    public function getTodaysTestLogs($token, $examinerId, $siteId = null)
    {
        $body = json_encode([
            'organisationId' => NULL,
            'siteId' => $siteId,
            'siteNr' => NULL,
            'personId' => NULL,
            'vehicleId' => NULL,
            'vehicleRegNr' => NULL,
            'vehicleVin' => NULL,
            'dateFromTs' => strtotime('today 01 am'),
            'dateToTs' => strtotime('tomorrow 01 am'),
            'status' =>
                array (
                    0 => 'ABANDONED',
                    1 => 'ABORTED',
                    2 => 'ABORTED_VE',
                    3 => 'FAILED',
                    4 => 'PASSED',
                    5 => 'REFUSED',
                ),
            'testType' =>
                array (
                    0 => 'NT',
                    1 => 'PL',
                    2 => 'PV',
                    3 => 'RT',
                ),
            'format' => 'DATA_CSV',
            'isSearchRecent' => false,
            'pageNr' => 1,
            'rowsCount' => 50000,
            'searchTerm' => NULL,
            'sortBy' => 'testDateTime',
            'sortDirection' => 'DESC',
            'start' => NULL,
            'filter' => NULL,
            'isApiGetData' => true,
            'isApiGetTotalCount' => false,
            'isEsEnabled' => NULL,
            '_class' => 'DvsaCommon\Dto\Search\MotTestSearchParamsDto',
        ]);

        return $this->client->request(new Request(
            MotApi::METHOD_POST,
            str_replace('{authorised_examiner_id}', $examinerId, self::TEST_LOGS),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
            $body
        ));
    }
}
