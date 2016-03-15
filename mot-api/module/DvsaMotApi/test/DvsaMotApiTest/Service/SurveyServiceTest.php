<?php

namespace DvsaMotApiTest\Service;

use Doctrine\ORM\EntityManager;
use DvsaMotApi\Service\SurveyService;

class SurveyServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * EntityManager
     */
    private $entityManagerMock;

    public function setUp()
    {
        $this->entityManagerMock = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * @return array
     */
    public function testCreateSurveyResultProvider()
    {
        return [
            [null],
            [1],
            [2],
            [3],
            [4],
            [5],
        ];
    }

    /**
     * @dataProvider testCreateSurveyResultProvider
     * @param $satisfactionRating
     */
    public function testCreateSurveyResult($satisfactionRating)
    {
        $service = $this->createSurveyService();

        $surveyResult = $service->createSurveyResult(['satisfaction_rating' => $satisfactionRating]);

        $this->assertEquals($satisfactionRating, $surveyResult['satisfaction_rating']);
    }

    /**
     * @return SurveyService
     */
    private function createSurveyService()
    {
        return new SurveyService($this->entityManagerMock);
    }
}