<?php

namespace SiteTest\Controller;

use Core\Service\MotFrontendAuthorisationServiceInterface;
use DvsaClient\Entity\SiteDailyOpeningHours;
use DvsaClient\Mapper\VehicleTestingStationMapper;
use DvsaClient\MapperFactory;
use DvsaCommon\Date\Time;
use DvsaCommonTest\Bootstrap;
use CoreTest\Controller\AbstractFrontendControllerTestCase;
use DvsaCommonTest\TestUtils\XMock;
use Site\Controller\SiteTestingDailyScheduleController;

/**
 * Class SiteTestingDailyScheduleControllerTest
 *
 * Tests the functionality of the controller and the output of its associated mapper.
 *
 * @package SiteTest\Controller
 */
class SiteTestingDailyScheduleControllerTest extends AbstractFrontendControllerTestCase
{
    private $siteId = 1;
    private $vtsMapper;
    private $mapperFactoryMock;

    public function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->setServiceManager($serviceManager);
        $serviceManager->setAllowOverride(true);

        $this->controller = new SiteTestingDailyScheduleController();

        $this->vtsMapper = $this->getVehicleTestingStationMapperMock();

        $this->mapperFactoryMock = $this->getMapperFactoryMock($this->vtsMapper);
        $serviceManager->setService(MapperFactory::class, $this->mapperFactoryMock);
        $authorisationService = XMock::of(MotFrontendAuthorisationServiceInterface::class);
        $serviceManager->setService('AuthorisationService', $authorisationService);

        $this->controller->setServiceLocator($serviceManager);

        $this->createHttpRequestForController('siteTestingDailySchedule');
    }

    private function getVehicleTestingStationMapperMock()
    {
        $vtsMapperMock = \DvsaCommonTest\TestUtils\XMock::of(VehicleTestingStationMapper::class);

        $vtsMapperMock->expects($this->any())
            ->method('getById')
            ->with(1)
            ->will($this->returnValue($this->getDummyScheduleObjects()));

        return $vtsMapperMock;
    }

    private function getMapperFactoryMock($vtsMapperMock)
    {
        $mapperFactoryMock = \DvsaCommonTest\TestUtils\XMock::of(MapperFactory::class);

        $mapperFactoryMock->expects($this->any())
            ->method('__get')
            ->will($this->returnValue($vtsMapperMock));

        return $mapperFactoryMock;
    }

    public function testEditAction_withValidData_canBeAccessed()
    {
        $response = $this->getResponseForAction(
            'edit',
            [
                'siteId' => $this->siteId,
            ]
        );
        $this->assertEquals(self::HTTP_OK_CODE, $response->getStatusCode());
    }

    public function testEditAction_withValidData_shouldReturnScheduleFormData()
    {
        //given
        $this->routeMatch->setParam('siteId', $this->siteId);

        //when
        $result = $this->controller->editAction();

        //then
        $this->assertEquals($this->getDummyScheduleData(), $result);
    }

    private function getDummyScheduleObjects()
    {
        $weeklySchedule = [];

        for ($i = 1; $i < 8; $i++) {
            $tmp = new SiteDailyOpeningHours();
            $tmp->setWeekday($i)
                ->setOpenTime(Time::fromIso8601("09:00:00"))
                ->setCloseTime(Time::fromIso8601("17:00:00"));
            $weeklySchedule['siteOpeningHours'][] = $tmp;
        }

        return $weeklySchedule;
    }

    private function getDummyScheduleData()
    {
        $weeklySchedule = [];
        $weeklySchedule['siteId'] = 1;
        $weeklySchedule['siteOpeningHours'] = [
            'mondayOpenTime' => '9.00',
            'mondayOpenTimePeriod' => 'am',
            'mondayCloseTime' => '5.00',
            'mondayCloseTimePeriod' => 'pm',
            'mondayIsClosed' => '',
            'tuesdayOpenTime' => '9.00',
            'tuesdayOpenTimePeriod' => 'am',
            'tuesdayCloseTime' => '5.00',
            'tuesdayCloseTimePeriod' => 'pm',
            'tuesdayIsClosed' => '',
            'wednesdayOpenTime' => '9.00',
            'wednesdayOpenTimePeriod' => 'am',
            'wednesdayCloseTime' => '5.00',
            'wednesdayCloseTimePeriod' => 'pm',
            'wednesdayIsClosed' => '',
            'thursdayOpenTime' => '9.00',
            'thursdayOpenTimePeriod' => 'am',
            'thursdayCloseTime' => '5.00',
            'thursdayCloseTimePeriod' => 'pm',
            'thursdayIsClosed' => '',
            'fridayOpenTime' => '9.00',
            'fridayOpenTimePeriod' => 'am',
            'fridayCloseTime' => '5.00',
            'fridayCloseTimePeriod' => 'pm',
            'fridayIsClosed' => '',
            'saturdayOpenTime' => '9.00',
            'saturdayOpenTimePeriod' => 'am',
            'saturdayCloseTime' => '5.00',
            'saturdayCloseTimePeriod' => 'pm',
            'saturdayIsClosed' => '',
            'sundayOpenTime' => '9.00',
            'sundayOpenTimePeriod' => 'am',
            'sundayCloseTime' => '5.00',
            'sundayCloseTimePeriod' => 'pm',
            'sundayIsClosed' => ''
        ];
        $weeklySchedule['errorData'] = [];
        $weeklySchedule['vtsName'] = 'unknown';
        return $weeklySchedule;
    }
}
