<?php

require_once 'configure_autoload.php';

use DvsaCommon\Dto\Search\MotTestSearchParamsDto;
use DvsaCommon\Utility\DtoHydrator;
use MotFitnesse\Util\OrganisationUrlBuilder;
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
            OrganisationUrlBuilder::sites($this->testOrgId)->toString(),
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
/*
| orgId? | siteId? | aedmId? |testerUserId? |
| $orgId1= | $siteId1= | $aedmId1= | $personId1= |
| $orgId1=| $siteId2= | $aedmId2= | $personId2= |
| $orgId2=| $siteId3= | $aedmId3= | $personId3= |


Check permission against default organisation (O1) and site (S1).
Test creates two organisation (O1 and O2) and 4 sites (S1, S2, S3, S4).

- accessMotTestLogData: Check permission to get Mot Test Log Data (OrganisationApi\Service\MotTestLogService);
- accessMotTestLogSummary: Check permission to get Mot Test Log Summary Data (DvsaElasticSearch\Service\findTestsLog);
- accessListOfSites: Check permission to get list of VTS sites by Organisation Id (OrganisationApi\Service\SiteService\getListForOrganisation());
- accessAeListByPersonId: Check permission to get list of AE by Person Id (OrganisationApi\Service\getAuthorisedExaminersForPerson);

!|MotFitnesse_Organisation_CheckPermissionById                                                                            |
|role          |hostOrg|hostSite|accessMotTestLogData?|accessMotTestLogSummary?|accessListOfSites?|accessAeListByPersonId?|
|TESTER        |O1     |S1      |OK                   |OK                      |OK                |Forbidden              |
|TESTER        |O2     |S3      |Forbidden            |Forbidden               |Forbidden         |Forbidden              |
|SITE-MANAGER  |O1     |S1      |Forbidden            |Forbidden               |OK                |Forbidden              |
|SITE-MANAGER  |O2     |S3      |Forbidden            |Forbidden               |Forbidden         |Forbidden              |
|SITE-ADMIN    |O1     |S1      |Forbidden            |Forbidden               |OK                |Forbidden              |
|SITE-ADMIN    |O2     |S3      |Forbidden            |Forbidden               |Forbidden         |Forbidden              |
|AED           |O1     |        |OK                   |OK                      |OK                |OK                     |
|AED           |O2     |        |Forbidden            |Forbidden               |Forbidden         |Forbidden              |
|AEDM          |O1     |        |OK                   |OK                      |OK                |OK                     |
|AEDM          |O2     |        |Forbidden            |Forbidden               |Forbidden         |Forbidden              |
|VE            |       |        |OK                   |OK                      |OK                |OK                     |
|AREA-OFFICE   |       |        |OK                   |OK                      |OK                |OK                     |
|DVLA-OPER     |       |        |Forbidden            |Forbidden               |Forbidden         |Forbidden              |
|CSCO          |       |        |Forbidden            |Forbidden               |OK                |Forbidden              |
|SCHEME-MANAGER|       |        |Forbidden            |Forbidden               |OK                |OK                     |

 */