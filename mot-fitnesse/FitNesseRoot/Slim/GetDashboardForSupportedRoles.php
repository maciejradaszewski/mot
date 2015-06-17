<?php
use DvsaCommon\Constants\Role;
use MotFitnesse\Util\DashboardUrlBuilder;

class GetDashboardForSupportedRoles
{
    /** @var string */
    private $roleCode;

    /** @var array dashboard data */
    private $dashboard;

    /** @var string */
    private $userName;

    /** @var string */
    private $userPassword;

    /** @var string */
    private $personId;

    public function setRoleCode($roleCode)
    {
        $this->roleCode = $roleCode;
    }

    public function dashboardType()
    {
        return $this->dashboard['hero'];
    }

    public function execute()
    {
        $this->createUser();
        $this->fetchDashboard();
    }

    private function createUser()
    {
        $testSupportHelper = new TestSupportHelper();
        switch ($this->roleCode) {
            case Role::USER:
                $result = $testSupportHelper->createUser();
                break;
            case Role::VEHICLE_EXAMINER:
                $result = $testSupportHelper->createVehicleExaminer();
                break;
            case Role::DVSA_AREA_OFFICE_1:
                $result = $testSupportHelper->createAreaOffice1User();
                break;
            case Role::CUSTOMER_SERVICE_CENTRE_OPERATIVE:
                $result = $testSupportHelper->createCustomerServiceCentreOperative();
                break;
            case Role::DVLA_OPERATIVE:
                $result = $testSupportHelper->createDvlaOperative();
                break;
            default:
                throw new \Exception("role " . $this->roleCode . " not supported");
        }

        $this->userName = $result['username'];
        $this->userPassword = $result['password'];
        $this->personId = $result['personId'];
    }

    private function fetchDashboard()
    {
        $url = DashboardUrlBuilder::dashboard($this->personId);
        $client = FitMotApiClient::create($this->userName, $this->userPassword);

        $this->dashboard = $client->get($url);
    }
}
