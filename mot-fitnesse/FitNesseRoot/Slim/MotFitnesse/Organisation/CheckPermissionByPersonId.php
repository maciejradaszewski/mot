<?php
use MotFitnesse\Util\PersonUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Check Permission on access to OrganisationApi services by PersonId
 */
class MotFitnesse_Organisation_CheckPermissionByPersonId
{
    private $resultAEListByPersonId;

    private $username;

    private $aedIdToCheck;

    public function __construct($aedIdToCheck)
    {
        $this->aedIdToCheck = $aedIdToCheck;
    }

    /**
     * Get username from test content to make request under this user
     *
     * @param $value
     */
    public function setUsername($value)
    {
        $this->username = $value;
    }

    public function setRole($value)
    {
        //  just for presentation
    }

    public function execute()
    {
        $this->requestListOfAuthorisedExaminersByPersonId();
    }

    /**
     * Make request to API to get list of Authorised examiners for specified Person Id
     *
     * @throws Exception
     */
    private function requestListOfAuthorisedExaminersByPersonId()
    {
        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            PersonUrlBuilder::byId($this->aedIdToCheck)->authorisedExaminer()->toString(),
            TestShared::METHOD_GET,
            null,
            $this->username,
            TestShared::PASSWORD
        );

        $this->resultAEListByPersonId = TestShared::execCurlForJson($curlHandle);
    }

    public function accessAeListByPersonId()
    {
        return TestShared::resultIsSuccess($this->resultAEListByPersonId)
            ? 'OK'
            : TestShared::errorMessages($this->resultAEListByPersonId);
    }
}
