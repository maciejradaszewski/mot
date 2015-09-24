<?php

require_once 'configure_autoload.php';

use DvsaCommon\Dto\Search\MotTestSearchParamsDto;
use DvsaCommon\Utility\DtoHydrator;
use MotFitnesse\Util\AuthorisedExaminerUrlBuilder;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

/**
 * Class MotFitnesse_Organisation_MotTestLogPermissionCheck
 */
class MotFitnesse_Organisation_CheckPermissionByOrgId
{
    private $resultMotTestLogSummary;
    private $resultMotTestLogData;
    private $resultListOfSites;

    private $username;

    private $testOrgId;

    public function __construct($orgIdToCheck)
    {
        $this->testOrgId = $orgIdToCheck;
    }

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
        $this->requestTestLogSummary();
        $this->requestTestLogData();
        $this->requestListOfSites();
    }

    private function requestTestLogSummary()
    {
        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            UrlBuilder::motTestLogSummary($this->testOrgId)->toString(),
            TestShared::METHOD_GET,
            null,
            $this->username,
            TestShared::PASSWORD
        );

        $this->resultMotTestLogSummary = TestShared::execCurlForJson($curlHandle);
    }

    private function requestTestLogData()
    {
        $dto = new MotTestSearchParamsDto();
        $dto->setOrganisationId($this->testOrgId);

        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            UrlBuilder::motTestLogSummary($this->testOrgId)->toString(),
            TestShared::METHOD_POST,
            DtoHydrator::dtoToJson($dto),
            $this->username,
            TestShared::PASSWORD
        );

        $this->resultMotTestLogData = TestShared::execCurlForJson($curlHandle);
    }

    private function requestListOfSites()
    {
        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            AuthorisedExaminerUrlBuilder::site($this->testOrgId)->toString(),
            TestShared::METHOD_GET,
            null,
            $this->username,
            TestShared::PASSWORD
        );

        $this->resultListOfSites = TestShared::execCurlForJson($curlHandle);
    }


    public function accessListOfSites()
    {
        return TestShared::resultIsSuccess($this->resultListOfSites)
            ? 'OK'
            : TestShared::errorMessages($this->resultListOfSites);
    }

    public function accessMotTestLogData()
    {
        return TestShared::resultIsSuccess($this->resultMotTestLogData)
            ? 'OK'
            : TestShared::errorMessages($this->resultMotTestLogData);
    }

    public function accessMotTestLogSummary()
    {
        return TestShared::resultIsSuccess($this->resultMotTestLogSummary)
            ? 'OK'
            : TestShared::errorMessages($this->resultMotTestLogSummary);
    }
}
