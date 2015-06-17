<?php

namespace MotFitnesse\Testing\Authorisation;


abstract class AbstractAuthorisationTest
{
    /** @var \TestSupportHelper */
    private $testSupportHelper;

    private $userNames;

    private $ourVtsId;

    private $vtsInSameAe;

    private $otherVtsId;

    private $ourAeId;

    private $otherAeId;

    protected function getHelper()
    {
        return $this->testSupportHelper;
    }

    protected function getVtsId()
    {
        return $this->ourVtsId;
    }

    protected function getAeId()
    {
        return $this->ourAeId;
    }

    public function __construct()
    {
        $this->testSupportHelper = new \TestSupportHelper();
    }

    /**
     * @param $username
     *
     * Implementation of this method should call the API resource you want.
     */
    abstract protected function callApi($username);

    private function doCall($username)
    {
        try {
            $this->callApi($username);
        } catch (\ApiErrorException $ex) {
            if ($ex->isForbiddenException()) {
                return "NO";
            }

            throw $ex;
        }

        return "YES";
    }

    public function setUpTestData()
    {
        $this->userNames = [];

        $aO1UserName = $this->testSupportHelper->createAreaOffice1User()['username'];

        $this->ourAeId = $this->testSupportHelper->createAuthorisedExaminer($aO1UserName)['id'];
        $this->otherAeId = $this->testSupportHelper->createAuthorisedExaminer($aO1UserName)['id'];

        $this->ourVtsId = $this->testSupportHelper->createVehicleTestingStation($aO1UserName, $this->ourAeId)['id'];
        $this->vtsInSameAe = $this->testSupportHelper->createVehicleTestingStation($aO1UserName, $this->ourAeId)['id'];
        $this->otherVtsId = $this->testSupportHelper->createVehicleTestingStation($aO1UserName, $this->otherAeId)['id'];

        $this->userNames['AREA-OFFICE-1'] = $aO1UserName;
        $this->userNames['VEHICLE-EXAMINER'] = $this->testSupportHelper->createVehicleExaminer()['username'];
        $this->userNames['DVLA-OPERATIVE'] = $this->testSupportHelper->createDvlaOperative()['username'];
        $this->userNames['CUSTOMER-SERVICE-CENTRE-OPERATIVE']
            = $this->testSupportHelper->createCustomerServiceCentreOperative()['username'];

        $schemeMangerName = $this->testSupportHelper->createSchemeManager()['username'];
        $this->userNames['SCHEME-MANAGER'] = $schemeMangerName;

        $this->userNames['TESTER-AT-VTS'] = $this->testSupportHelper->createTester(
                                                $aO1UserName, [$this->ourVtsId]
                                            )['username'];
        $this->userNames['TESTER-AT-SAME-AE-DIFFERENT-VTS'] = $this->testSupportHelper->createTester(
                                                                  $aO1UserName, [$this->vtsInSameAe]
                                                              )['username'];
        $this->userNames['TESTER-AT-DIFFERENT-AE-DIFFERENT-VTS'] = $this->testSupportHelper->createTester(
                                                                       $aO1UserName, [$this->otherVtsId]
                                                                   )['username'];

        $this->userNames['SITE-MANAGER-AT-VTS'] = $this->testSupportHelper->createSiteManager(
                                                      $aO1UserName, [$this->ourVtsId]
                                                  )['username'];
        $this->userNames['SITE-MANAGER-AT-SAME-AE-DIFFERENT-VTS'] = $this->testSupportHelper->createSiteManager(
                                                                        $aO1UserName, [$this->vtsInSameAe]
                                                                    )['username'];
        $this->userNames['SITE-MANAGER-AT-DIFFERENT-AE-DIFFERENT-VTS'] = $this->testSupportHelper->createSiteManager(
                                                                             $aO1UserName, [$this->otherVtsId]
                                                                         )['username'];

        $this->userNames['SITE-ADMIN-AT-VTS'] = $this->testSupportHelper->createSiteAdmin(
                                                    $aO1UserName, [$this->ourVtsId]
                                                )['username'];
        $this->userNames['SITE-ADMIN-AT-SAME-AE-DIFFERENT-VTS'] = $this->testSupportHelper->createSiteAdmin(
                                                                      $aO1UserName, [$this->vtsInSameAe]
                                                                  )['username'];
        $this->userNames['SITE-ADMIN-AT-DIFFERENT-AE-DIFFERENT-VTS'] = $this->testSupportHelper->createSiteAdmin(
                                                                           $aO1UserName, [$this->otherVtsId]
                                                                       )['username'];

        $this->userNames['AEDM-AT-AE'] = $this->testSupportHelper->createAuthorisedExaminerDesignatedManagement(
                                             $schemeMangerName, null, [$this->ourAeId]
                                         )['username'];
        $this->userNames['AEDM-AT-DIFFERENT-AE']
            = $this->testSupportHelper->createAuthorisedExaminerDesignatedManagement(
                  $schemeMangerName, null, [$this->otherAeId]
              )['username'];

        $this->userNames['AED-AT-AE'] = $this->testSupportHelper->createAuthorisedExaminerDelegate(
                                            $schemeMangerName, null, [$this->ourAeId]
                                        )['username'];
        $this->userNames['AED-AT-DIFFERENT-AE'] = $this->testSupportHelper->createAuthorisedExaminerDelegate(
                                                      $schemeMangerName, null, [$this->otherAeId]
                                                  )['username'];
    }

    public function testerAtSite()
    {
        return $this->doCall($this->userNames['TESTER-AT-VTS']);
    }

    public function testerAtSameAeDifferentVts()
    {
        return $this->doCall($this->userNames['TESTER-AT-SAME-AE-DIFFERENT-VTS']);
    }

    public function testerAtDifferentAeDifferentVts()
    {
        return $this->doCall($this->userNames['TESTER-AT-DIFFERENT-AE-DIFFERENT-VTS']);
    }

    public function siteManagerAtSite()
    {
        return $this->doCall($this->userNames['SITE-MANAGER-AT-VTS']);
    }

    public function siteManagerAtSameAeDifferentVts()
    {
        return $this->doCall($this->userNames['SITE-MANAGER-AT-SAME-AE-DIFFERENT-VTS']);
    }

    public function siteManagerAtDifferentAeDifferentVts()
    {
        return $this->doCall($this->userNames['SITE-MANAGER-AT-DIFFERENT-AE-DIFFERENT-VTS']);
    }

    public function siteAdminAtSite()
    {
        return $this->doCall($this->userNames['SITE-ADMIN-AT-VTS']);
    }

    public function siteAdminAtSameAeDifferentVts()
    {
        return $this->doCall($this->userNames['SITE-ADMIN-AT-SAME-AE-DIFFERENT-VTS']);
    }

    public function siteAdminAtDifferentAeDifferentVts()
    {
        return $this->doCall($this->userNames['SITE-ADMIN-AT-DIFFERENT-AE-DIFFERENT-VTS']);
    }

    public function aedmAtAe()
    {
        return $this->doCall($this->userNames['AEDM-AT-AE']);
    }

    public function aedmAtDifferentAe()
    {
        return $this->doCall($this->userNames['AEDM-AT-DIFFERENT-AE']);
    }

    public function aedAtAe()
    {
        return $this->doCall($this->userNames['AED-AT-AE']);
    }

    public function aedAtDifferentAe()
    {
        return $this->doCall($this->userNames['AED-AT-DIFFERENT-AE']);
    }

    public function areaOffice1()
    {
        return $this->doCall($this->userNames['AREA-OFFICE-1']);
    }

    public function vehicleExaminer()
    {
        return $this->doCall($this->userNames['VEHICLE-EXAMINER']);
    }

    public function dvlaOperative()
    {
        return $this->doCall($this->userNames['DVLA-OPERATIVE']);
    }

    public function customerServiceCentreOperative()
    {
        return $this->doCall($this->userNames['CUSTOMER-SERVICE-CENTRE-OPERATIVE']);
    }

    public function schemeManager()
    {
        return $this->doCall($this->userNames['SCHEME-MANAGER']);
    }
}
