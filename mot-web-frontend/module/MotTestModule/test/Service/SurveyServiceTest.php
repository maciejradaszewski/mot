<?php

namespace Dvsa\Mot\Frontend\MotTestModuleTest\Service;

use Dvsa\Mot\Frontend\MotTestModule\Service\SurveyService;
use DvsaCommon\HttpRestJson\Client;

class SurveyServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client $serviceMock
     */
    private $clientMock;

    /**
     * @var SurveyService $serviceMock
     */
    private $serviceMock;

    const API_URL = 'survey';

    public function setUp()
    {
        $this->clientMock = $this->getMockBuilder(Client::class)->getMock();

        $this->serviceMock = new SurveyService($this->clientMock);
    }

    /**
     * @dataProvider testSubmitSurveyDataProvider
     * @group survey_service_tests
     */
    public function testSubmitSurveyResult($feedback, $expected)
    {
        $this->clientMock
            ->expects($this->once())
            ->method('post')
            ->with(self::API_URL, $feedback)
            ->willReturn([$feedback]);

        $result = $this->serviceMock->submitSurveyResult($feedback);

        $this->assertSame($result, $expected);
    }

    public function testSubmitSurveyDataProvider()
    {
        return [
            [
                '1',
                ['1'],
            ],
            [
                '2',
                ['2'],
            ],
            [
                '3',
                ['3'],
            ],
            [
                '4',
                ['4'],
            ],
            [
                '5',
                ['5'],
            ],
        ];
    }
}