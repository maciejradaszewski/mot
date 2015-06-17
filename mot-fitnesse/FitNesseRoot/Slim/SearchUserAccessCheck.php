<?php
use MotFitnesse\Util\UrlBuilder;

/**
 * VM-8430, sub task of: VM-7646
 *
 * As a CSCO/VE and AO, I would like to ability to search for a person.
 */
class SearchUserAccessCheck
{
    private $user;

    /** @var array */
    private $searchParams;

    /** @var array */
    private $resultData;

    /** @var bool */
    private $error;

    /** @var string */
    private $errorMessage;

    /** @var \TestSupportHelper */
    private $testSupportHelper;

    private $userType;

    private $targetPersonId;

    public function __construct()
    {
        $this->testSupportHelper = new TestSupportHelper();
    }

    public function reset()
    {
        $this->searchParams = [];
        $this->resultData = [];
        $this->error = false;
        $this->errorMessage = '';
    }

    public function setUserType($userType)
    {
        $this->userType = $userType;
    }

    /** @return bool */
    public function error()
    {
        return $this->error;
    }

    /** @return string */
    public function errorMessage()
    {
        return $this->errorMessage;
    }

    public function execute()
    {
        switch ($this->userType) {
            case 'tester':
                $this->setupTargetTester();
                break;

            case 'aedm':
                $this->setupTargetAedm();
                break;

            case 'schememgmt':
                $this->setupTargetSchemeMgmt();
                break;

            case 'aed':
                $this->setupTargetAed();
                break;

            case 'csco':
                $this->setupTargetCsco();
                break;

            case 'vehicle-examiner':
                $this->setupTargetVe();
                break;

            case 'areaoffice':
                $this->setupTargetAo();
                break;
        }

        $apiClient = FitMotApiClient::create($this->user['username'], $this->user['password']);
        $urlBuilder = UrlBuilder::create()->searchPerson();

        $this->setupTargetTester();
        $urlBuilder->queryParam('username', $this->user['username']);

        try {
            $this->resultData = $apiClient->get($urlBuilder);
        } catch (ApiErrorException $ex) {
            $this->error = true;
            $this->errorMessage = $ex->getMessage();
        }
    }

    private function setupTargetTester()
    {
        $schememgt = $this->testSupportHelper->createSchemeManager();
        $this->user = $this->testSupportHelper->createTester($schememgt['username'], [1]);
    }

    private function setupTargetAe()
    {
        $this->user = $this->testSupportHelper->createAuthorisedExaminer(
            $this->testSupportHelper->createAreaOffice1User()['username']
        );

        $this->targetPersonId = $this->user['id'];
    }

    private function setupTargetAed()
    {
        $this->setupTargetAe();

        $this->user = $this->testSupportHelper->createAuthorisedExaminerDelegate(
            $this->testSupportHelper->createAreaOffice2User()['username'],
            null,
            [$this->targetPersonId]
        );
    }

    private function setupTargetAedm()
    {
        $this->setupTargetAe();

        $this->user = $this->testSupportHelper->createAuthorisedExaminerDesignatedManagement(
            $this->testSupportHelper->createAreaOffice2User()['username'],
            null,
            [$this->targetPersonId]
        );
    }

    private function setupTargetSchemeMgmt()
    {
        $this->user = $this->testSupportHelper->createSchemeManager();
    }

    private function setupTargetCsco()
    {
        $this->user =  $this->testSupportHelper->createCustomerServiceCentreOperative();
    }

    private function setupTargetAo()
    {
        $this->user = $this->testSupportHelper->createAreaOffice1User();
    }

    private function setupTargetVe()
    {
        $this->user = $this->testSupportHelper->createVehicleExaminer();
    }

}
