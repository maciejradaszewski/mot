<?php

require_once 'configure_autoload.php';

use DvsaCommon\Enum\OrganisationBusinessRoleCode;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;
use DvsaCommon\Enum\BusinessRoleStatusCode;
use MotFitnesse\Util\CredentialsProvider;

class VM2546OrganisationPositionNomination
{
    const ORGANISATION_POSITION_ID = 2;
    const ORGANISATION_NOMINATION_ACCEPTED = 'ORGANISATION-NOMINATION-ACCEPTED';
    const ORGANISATION_NOMINATION_REJECTED = 'ORGANISATION-NOMINATION-REJECTED';

    private $recipient;
    private $ae;
    private $response;

    private $isSuccess;
    private $result;

    private $recipientUsername;
    private $notification;
    private $aedmUsername;

    public function __construct($aedmUsername)
    {
        $this->aedmUsername = $aedmUsername;
    }

    public function success()
    {
        $nomination = $this->inputAedNomination();

        $urlBuilder = UrlBuilder::organisationPositionNomination($this->getAe());

        $this->result = TestShared::execCurlFormPostForJsonFromUrlBuilder(
            $this->createCredentialProvider($this->aedmUsername), $urlBuilder, $nomination
        );
        $this->isSuccess = TestShared::resultIsSuccess($this->result);

        if (isset($this->result['data']['id'])) {
            $result = TestShared::execCurlForJsonFromUrlBuilder(
                $this->createCredentialProvider($this->recipientUsername, TestShared::PASSWORD),
                $urlBuilder->notificationForPerson($this->getRecipient())
            );

            if (empty($result) || empty($result['data']) || empty($result['data'][0])) {
                throw new \OutOfBoundsException(
                    'Wrong data structure, expected array([data => [0 => [id => ...]]]), given ' .
                    json_encode($result)
                );
            }
            $this->notification = $result['data'][0]['id'];
        }

        return $this->isSuccess;
    }

    public function responseSent()
    {
        if ($this->isSuccess) {
            $nomination = $this->inputResponse();

            $urlBuilder = UrlBuilder::notification($this->notification)->action();

            $this->result = TestShared::execCurlFormPutForJsonFromUrlBuilder(
                $this->createCredentialProvider($this->recipientUsername), $urlBuilder, $nomination
            );

            $this->isSuccess = TestShared::resultIsSuccess($this->result);
            return $this->isSuccess;
        }
        return false;
    }

    /**
     * @param string $username
     *
     * @return CredentialsProvider
     */
    private function createCredentialProvider($username)
    {
        return new CredentialsProvider($username, TestShared::PASSWORD);
    }

    public function recipientBecomeAed()
    {
        if ($this->isSuccess) {

            if (false === $this->hasRole($this->getRecipient())) {
                return false;
            }

            $urlBuilder = UrlBuilder::organisationPositionNomination($this->getAe());

            $result = TestShared::get(
                $urlBuilder->toString(), $this->aedmUsername, TestShared::PASSWORD
            );

            if (is_array($result)) {
                $n = count($result);

                while ($n--) {
                    if ($this->isPersonAed($result[$n], $this->getRecipient())) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    private function hasRole($personId)
    {
        $urlBuilder = (new UrlBuilder)->personalDetails()->routeParam('id', $personId);

        $result = TestShared::get(
            $urlBuilder->toString(), $this->recipientUsername, TestShared::PASSWORD
        );

        return in_array("AED", $this->getRoles($result['roles']));
    }

    private function getRoles(array $rolesAndAssociations)
    {
        $roles = $rolesAndAssociations["system"]['roles'];

        foreach ($rolesAndAssociations["organisations"] as $id=>$org) {
            $roles = array_merge($roles, $org["roles"]);
        }

        foreach ($rolesAndAssociations["sites"] as $id=>$site) {
            $roles = array_merge($roles, $site["roles"]);
        }

        return $roles;
    }

    private function isPersonAed($organisation, $person)
    {
        return $organisation['role'] == OrganisationBusinessRoleCode::AUTHORISED_EXAMINER_DELEGATE
        && $organisation['person']['id'] == $person
        && $organisation['status'] === BusinessRoleStatusCode::ACTIVE;
    }

    public function errorMessages()
    {
        return TestShared::errorMessages($this->result);
    }

    /**
     * @param mixed $ae
     *
     * @return $this
     */
    public function setAe($ae)
    {
        $this->ae = $ae;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAe()
    {
        return $this->ae;
    }

    /**
     * @param mixed $recipient
     *
     * @return $this
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param mixed $response
     *
     * @return $this
     */
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        if ('accepted' === $this->response) {
            return self::ORGANISATION_NOMINATION_ACCEPTED;
        }
        if ('rejected' === $this->response) {
            return self::ORGANISATION_NOMINATION_REJECTED;
        }
        return 'N/A';
    }

    /**
     * @param mixed $result
     *
     * @return $this
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    public function setInfo()
    {
    }

    /**
     * @return array
     */
    protected function inputAedNomination()
    {
        return [
            'nomineeId' => $this->getRecipient(),
            'roleId'    => self::ORGANISATION_POSITION_ID,
        ];
    }

    /**
     * @return array
     */
    protected function inputResponse()
    {
        return [
            'action' => $this->getResponse()
        ];
    }

    /**
     * @param mixed $recipientUsername
     */
    public function setRecipientUsername($recipientUsername)
    {
        $this->recipientUsername = $recipientUsername;
    }
}
