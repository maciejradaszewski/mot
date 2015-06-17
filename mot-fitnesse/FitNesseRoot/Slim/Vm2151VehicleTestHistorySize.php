<?php

require_once 'configure_autoload.php';

use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Enum\MotTestStatusName;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\VehicleUrlBuilder;

class Vm2151VehicleTestHistorySize
{
    private $role;
    private $vehicleId;

    public function beginTable()
    {
        $date16m = DateUtils::subtractCalendarMonths(new \DateTime(), '16');
        $date17m = DateUtils::subtractCalendarMonths(new \DateTime(), '17');
        $date18m = DateUtils::subtractCalendarMonths(new \DateTime(), '18');
        $date19m = DateUtils::subtractCalendarMonths(new \DateTime(), '19');

        $createVehicleCredentials = FitMotApiClient::create(TestShared::USERNAME_TESTER1, TestShared::PASSWORD);
        $this->vehicleId = (new VehicleTestHelper($createVehicleCredentials))->generateVehicle();

        $testSupportHelper = new TestSupportHelper();

        $schememgt = $testSupportHelper->createSchemeManager();
        $tester = $testSupportHelper->createTester($schememgt['username'], [2004]);

        $this->createTest($testSupportHelper, $tester['username'], $date16m, MotTestStatusName::ABANDONED);
        $this->createTest($testSupportHelper, $tester['username'], $date17m, MotTestStatusName::PASSED);
        $this->createTest($testSupportHelper, $tester['username'], $date18m, MotTestStatusName::PASSED);
        $this->createTest($testSupportHelper, $tester['username'], $date19m, MotTestStatusName::FAILED);
    }

    private $roleToUserNameMapping
        = [
            'TESTER'                 => 'tester1',
            'DVSA-AREA-OFFICE-1'     => 'areaoffice1user',
            'DVSA-SCHEME-MANAGEMENT' => 'schememgt'
        ];

    public function setRole($value)
    {
        $this->role = $value;
    }

    public function numberOfTests()
    {
        if (!array_key_exists($this->role, $this->roleToUserNameMapping)) {
            return 'INVALID ROLE';
        }

        $url = VehicleUrlBuilder::vehicle($this->vehicleId)->testHistory()->toString();

        $userName = $this->roleToUserNameMapping[$this->role];
        $password = TestShared::PASSWORD;

        $data = TestShared::get($url, $userName, $password);

        return count($data);
    }

    /**
     * @param TestSupportHelper $testSupportHelper
     * @param string            $username
     * @param \DateTime         $dateTime
     * @param string            $outcome
     */
    private function createTest(TestSupportHelper $testSupportHelper, $username, \DateTime $dateTime, $outcome)
    {
        $testSupportHelper->createMotTest(
            $username,
            2004,
            $this->vehicleId,
            $outcome,
            null,
            12345,
            [
                'startDate'     => DateTimeApiFormat::dateTime($dateTime),
                'issueDate'     => DateTimeApiFormat::date($dateTime),
                'completedDate' => DateTimeApiFormat::dateTime($dateTime),
                'expiryDate'    => DateTimeApiFormat::date($dateTime)
            ]
        );
    }
}
