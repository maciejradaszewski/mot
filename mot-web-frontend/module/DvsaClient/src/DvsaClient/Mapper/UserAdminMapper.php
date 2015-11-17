<?php

namespace DvsaClient\Mapper;

use DvsaCommon\Dto\Person\PersonContactDto;
use DvsaCommon\Dto\Person\PersonHelpDeskProfileDto;
use DvsaCommon\Dto\Person\SearchPersonResultDto;
use DvsaCommon\Dto\Security\SecurityQuestionDto;
use DvsaCommon\UrlBuilder\MessageUrlBuilder;
use DvsaCommon\UrlBuilder\PersonUrlBuilder;
use DvsaCommon\UrlBuilder\UserAdminUrlBuilder;

/**
 * Class UserAdminMapper
 *
 * @package DvsaClient\Mapper
 */
class UserAdminMapper extends DtoMapper
{
    /**
     * @param int       $personId
     * @param int       $questionId
     *
     * @return SecurityQuestionDto
     */
    public function getSecurityQuestion($questionId, $personId)
    {
        $url = UserAdminUrlBuilder::securityQuestionGet($questionId, $personId)->toString();
        return $this->get($url);
    }

    /**
     * @param int       $personId
     * @param int       $questionId
     * @param string    $answer
     *
     * @return bool
     */
    public function checkSecurityQuestion($questionId, $personId, $answer)
    {
        $url = UserAdminUrlBuilder::securityQuestionCheck($questionId, $personId)->toString();
        return $this->getWithParams($url, $answer);
    }

    /**
     * @param int $personId
     * @return PersonHelpDeskProfileDto
     */
    public function getUserProfile($personId)
    {
        $url = PersonUrlBuilder::helpDeskProfileUnrestricted($personId)->toString();

        $response = $this->client->get($url);

        return PersonHelpDeskProfileDto::fromArray($response['data']);
    }

    /**
     * @param $criteria
     * @return \DvsaCommon\Dto\Person\SearchPersonResultDto[]
     */
    public function searchUsers($criteria)
    {
        $url = PersonUrlBuilder::personSearch()->queryParams($criteria)->toString();
        $response = $this->client->get($url);

        return SearchPersonResultDto::getList($response['data']);
    }

    /**
     * @param $personId
     * @return bool
     */
    public function resetClaimAccount($personId)
    {
        $url = PersonUrlBuilder::resetClaimAccount($personId)->toString();
        return $this->client->get($url);
    }

    /**
     * @param $params
     * @return bool
     */
    public function postMessage($params)
    {
        $url = MessageUrlBuilder::message()->toString();
        return $this->client->post($url, $params);
    }

    /**
     * @param integer $personId
     * @param string $email
     * @throws RestApplicationException
     * @return PersonContactDto
     */
    public function updateEmail($personId, $email)
    {
        $url = UserAdminUrlBuilder::personContact($personId);
        return $this->client->patch($url, ["emails" => [$email]]);
    }

    /**
     * @param $personId
     * @param $licenceNumber
     * @param $licenceRegion
     * @return mixed|string
     */
    public function updateDrivingLicence($personId, $licenceNumber, $licenceRegion)
    {
        $url = UserAdminUrlBuilder::licenceDetails($personId);

        return $this->client->post(
            $url, [
                "drivingLicenceNumber" => $licenceNumber,
                "drivingLicenceRegion" => $licenceRegion,
            ]
        );
    }

    /**
     * @param int $personId
     * @return mixed|string
     */
    public function deleteDrivingLicence($personId)
    {
        $url = UserAdminUrlBuilder::licenceDetails($personId);

        return $this->client->delete($url);
    }
}
