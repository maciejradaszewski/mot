<?php

namespace DvsaMotApiTest\Service;

use Aws\S3\S3Client;
use Doctrine\ORM\EntityManager;
use DvsaMotApi\Service\SurveyService;

class SurveyServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * EntityManager.
     */
    private $entityManagerMock;

    /**
     * @var S3Client|\PHPUnit_Framework_MockObject_MockObject
     */
    private $s3ClientMock;

    private $surveyResults;

    public function setUp()
    {
        $this->entityManagerMock = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();

        $this->s3ClientMock = $this->getMockBuilder(S3Client::class)
            ->disableOriginalConstructor()
            ->setMethods(['putObject'])
            ->getMock();
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
     *
     * @param $satisfactionRating
     */
    public function testCreateSurveyResult($satisfactionRating)
    {
        $service = $this->createSurveyService();

        $surveyResult = $service->createSurveyResult(['satisfaction_rating' => $satisfactionRating]);

        $this->assertEquals($satisfactionRating, $surveyResult['satisfaction_rating']);
    }

    /**
     * @group survey_report_generation
     * @group integration
     */
    public function testGeneratingSurveyReports()
    {
        $service = $this->withSurveyResults()->createSurveyService();

        $csvHandle = fopen('php://memory', 'w');

        if (!empty(SurveyService::$CSV_COLUMNS)) {
            fputcsv($csvHandle, SurveyService::$CSV_COLUMNS);
        }

        $timeStamp = new \DateTime();
        $row['timestamp'] = $timeStamp->format('Y-m-d-H-i-s');
        $row['period'] = 'month';
        $row['slug'] = 'https://mot-testing.i-env.net/';
        $row['rating_1'] = 1;
        $row['rating_2'] = 2;
        $row['rating_3'] = 3;
        $row['rating_4'] = 4;
        $row['rating_5'] = 5;
        $row['total'] = 15;

        fputcsv($csvHandle, $row);
        rewind($csvHandle);

        foreach (([SurveyService::$CSV_COLUMNS]) as $line) {
            fputcsv($csvHandle, $line);
        }

        rewind($csvHandle);
        $expectedContent = stream_get_contents($csvHandle);
        fclose($csvHandle);

        $this->s3ClientMock
            ->expects($this->atLeastOnce())
            ->method('putObject')
            ->with([
                'Bucket' => '',
                'Key' => $timeStamp->format('Y-m'),
                'Body' => $expectedContent,
                'ContentType' => 'text/csv',
            ]);

        $service->generateSurveyReports($this->surveyResults);
    }

    /**
     * @return $this
     */
    private function withSurveyResults()
    {
        $this->surveyResults = [
            'rating_1' => 1,
            'rating_2' => 2,
            'rating_3' => 3,
            'rating_4' => 4,
            'rating_5' => 5,
            'total' => 15,
        ];

        return $this;
    }

    /**
     * @return SurveyService
     */
    private function createSurveyService()
    {
        return new SurveyService($this->entityManagerMock, $this->s3ClientMock, '');
    }
}
