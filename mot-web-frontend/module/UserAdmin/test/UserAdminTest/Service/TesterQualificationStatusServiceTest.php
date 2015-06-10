<?php

namespace UserAdminTest\Service;

use CoreTest\Service\StubCatalogService;
use DvsaClient\Mapper\TesterQualificationStatusMapper;
use DvsaCommon\Enum\AuthorisationForTestingMotStatusCode as Status;
use DvsaCommon\Enum\VehicleClassGroupCode as Group;
use DvsaCommonTest\TestUtils\XMock;
use UserAdmin\Service\TesterQualificationStatusService;

class TesterQualificationStatusServiceTest extends \PHPUnit_Framework_TestCase
{

    private $testerQualificationStatusMapperMock;

    private $catalogServiceMock;

    /** @var  TesterQualificationStatusService */
    private $service;

    public function setUp()
    {
        $this->testerQualificationStatusMapperMock = XMock::of(TesterQualificationStatusMapper::class);

        $this->catalogServiceMock = new StubCatalogService();

        $this->service = new TesterQualificationStatusService(
            $this->testerQualificationStatusMapperMock,
            $this->catalogServiceMock
        );
    }

    /**
     *
     * @dataProvider dpExpectedMapperDataAndGroupResult
     * @throws \UnexpectedValueException
     */
    public function testGetPersonGroupQualificationStatus(
        $mockedMapperResponse,
        $expectedGroup
    ) {

        $this->mockMapper($mockedMapperResponse);

        $this->assertEquals(
            $expectedGroup,
            $this->service->getPersonGroupQualificationStatus(5)
        );


    }

    public function testGetPersonGroupQualificationStatusSpotsMismatchStatusInGroupA()
    {
        $this->setExpectedException(
            \Exception::class,
            sprintf(TesterQualificationStatusService::ERR_MSG_DIFFERENT_CLASSES_IN_GROUP, 'A')
        );
        $this->mockMapper(
            [
                Status::QUALIFIED,
                Status::DEMO_TEST_NEEDED,
                Status::QUALIFIED,
                Status::QUALIFIED,
                Status::QUALIFIED,
                Status::QUALIFIED,
            ]
        );
        $this->service->getPersonGroupQualificationStatus(5);

    }

    /**
     * @throws \Exception
     */
    public function testExceptionOnUnexpectedClass()
    {
        $this->setExpectedException(
            \Exception::class,
            sprintf(TesterQualificationStatusService::ERR_MSG_UNEXPECTED_CLASSES, 'class6')
        );

        $this->mockMapper(
            [
                'class6' => Status::QUALIFIED,
                'c1' => Status::QUALIFIED,
                'cls2' => Status::QUALIFIED,
                'cls_3' => Status::QUALIFIED,
                'class_4' => Status::QUALIFIED,
                'Class_5' => Status::QUALIFIED,
            ]
            ,
            false
        );
        $this->service->getPersonGroupQualificationStatus(5);
    }

    /**
     * @throws \Exception
     */
    public function testExceptionOnNotUnifiedStatusInEachGroup()
    {
        $this->setExpectedException(
            \Exception::class,
            sprintf(TesterQualificationStatusService::ERR_MSG_DIFFERENT_CLASSES_IN_GROUP, 'B')
        );

        $this->mockMapper(
            [
                Status::QUALIFIED,
                Status::QUALIFIED,
                Status::QUALIFIED,
                Status::QUALIFIED,
                Status::DEMO_TEST_NEEDED,
                Status::QUALIFIED,
            ]
        );
        $this->service->getPersonGroupQualificationStatus(5);
    }

    public function testDoesPersonHasAnyQualification()
    {
        $this->mockMapper([Status::QUALIFIED, null, null, null, null, null,]);

        $this->assertTrue($this->service->doesPersonHasAnyQualification(5));
    }

    public function testDoesPersonHasAnyQualificationNegative()
    {
        $this->mockMapper([null, null, null, null, null, null,]);

        $this->assertFalse($this->service->doesPersonHasAnyQualification(5));
    }

    public function testGetGroupPrintableClasses()
    {
        $GroupsAndExpectedPrintableClasses = [
            'A' => 'Class 1, Class 2',
            'B' => 'Class 3, Class 4, Class 5, Class 7',
        ];

        foreach ($GroupsAndExpectedPrintableClasses as $group => $printableClasses) {
            $this->assertEquals(
                $printableClasses,
                $this->service->getGroupPrintableClasses($group)
            );
        }

    }


    /**
     * data provider
     *
     * @return array
     */
    public function dpExpectedMapperDataAndGroupResult()
    {

        return [
            [
                [
                    Status::QUALIFIED,
                    Status::QUALIFIED,
                    Status::QUALIFIED,
                    Status::QUALIFIED,
                    Status::QUALIFIED,
                    Status::QUALIFIED,
                ],
                [
                    Group::BIKES => $this->convertStatsCodeToName(Status::QUALIFIED),
                    Group::CARS_ETC => $this->convertStatsCodeToName(Status::QUALIFIED)
                ],
            ],
            [
                [
                    Status::DEMO_TEST_NEEDED,
                    Status::DEMO_TEST_NEEDED,
                    Status::QUALIFIED,
                    Status::QUALIFIED,
                    Status::QUALIFIED,
                    Status::QUALIFIED,
                ],
                [
                    Group::BIKES => $this->convertStatsCodeToName(Status::DEMO_TEST_NEEDED),
                    Group::CARS_ETC => $this->convertStatsCodeToName(Status::QUALIFIED)
                ],
            ],
            [
                [
                    Status::QUALIFIED,
                    Status::QUALIFIED,
                    Status::INITIAL_TRAINING_NEEDED,
                    Status::INITIAL_TRAINING_NEEDED,
                    Status::INITIAL_TRAINING_NEEDED,
                    Status::INITIAL_TRAINING_NEEDED,
                ],
                [
                    Group::BIKES => $this->convertStatsCodeToName(Status::QUALIFIED),
                    Group::CARS_ETC => $this->convertStatsCodeToName(Status::INITIAL_TRAINING_NEEDED)
                ],
            ],
            [
                [
                    Status::UNKNOWN,
                    Status::UNKNOWN,
                    Status::UNKNOWN,
                    Status::UNKNOWN,
                    Status::UNKNOWN,
                    Status::UNKNOWN,
                ],
                [
                    Group::BIKES => $this->convertStatsCodeToName(Status::UNKNOWN),
                    Group::CARS_ETC => $this->convertStatsCodeToName(Status::UNKNOWN)
                ],
            ],
            [
                [
                    null,
                    null,
                    Status::UNKNOWN,
                    Status::UNKNOWN,
                    Status::UNKNOWN,
                    Status::UNKNOWN,
                ],
                [
                    Group::BIKES => TesterQualificationStatusService::DEFAULT_NO_STATUS,
                    Group::CARS_ETC => $this->convertStatsCodeToName(Status::UNKNOWN)
                ],
            ],
            [
                [
                    Status::UNKNOWN,
                    Status::UNKNOWN,
                    null,
                    null,
                    null,
                    null,
                ],
                [
                    Group::BIKES => $this->convertStatsCodeToName(Status::UNKNOWN),
                    Group::CARS_ETC => TesterQualificationStatusService::DEFAULT_NO_STATUS,
                ],
            ],
        ];
    }


    /**
     * To set the mock response for testerQualificationStatusMapperMock
     * e.g.
     * pass 'QLFD' to copy for all classes
     * or ['DMTN','DMTN','QLFD','QLFD','QLFD','QLFD'] to copy individually for 6 existing class
     *
     * @param string|array $data
     * @throws \Exception
     */
    private function mockMapper($data, $processArray = true)
    {
        $classes = [
            'class1',
            'class2',
            'class3',
            'class4',
            'class5',
            'class7',
        ];

        $classCount = count($classes);

        if (is_array($data) && count($data) !== $classCount) {
            throw new \Exception(
                sprintf(
                    'Data must be a single status to be copied for all %s classes or an array with exactly %s status',
                    $classCount,
                    $classCount
                )
            );
        }


        if (is_array($data)) {

            $mockResponse = $processArray ? array_combine($classes, $data) : $data;

        } else {
            $mockResponse = array_map(
                function () use ($data) {
                    return $data;
                },
                array_flip($classes)
            );
        }

        $this->testerQualificationStatusMapperMock->expects($this->any())
            ->method('getTesterQualificationStatus')
            ->with(5)
            ->willReturn(['data' => $mockResponse]);
    }

    private function convertStatsCodeToName($code)
    {
        return (new StubCatalogService())->getQualificationStatus()[$code];
    }

}
