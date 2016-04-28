<?php

namespace DvsaMotApi\Service;

use Aws\S3\S3Client;
use Doctrine\ORM\EntityManager;
use DvsaCommonApi\Service\AbstractService;
use DvsaEntities\Entity\SurveyResult;

/**
 * Class SurveyService.
 */
class SurveyService extends AbstractService
{
    /**
     * @var S3Client
     */
    private $s3Client;

    /**
     * @var string
     */
    private $bucket;

    public static $CSV_COLUMNS = [
        'timestamp',
        'period',
        'slug',
        'rating_1',
        'rating_2',
        'rating_3',
        'rating_4',
        'rating_5',
        'total',
    ];

    /**
     * SurveyService constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager,
                                S3Client $s3Client,
                                $bucket)
    {
        parent::__construct($entityManager);
        $this->s3Client = $s3Client;
        $this->bucket = $bucket;
    }

    /**
     * @param array $data
     *
     * @return int
     */
    public function createSurveyResult(array $data)
    {
        $surveyResult = new SurveyResult();
        $surveyResult->setSatisfactionRating($data['satisfaction_rating']);
        $this->entityManager->persist($surveyResult);
        $this->entityManager->flush();

        return ['satisfaction_rating' => $surveyResult->getSatisfactionRating()];
    }

    /**
     * @param $rating
     *
     * @return array
     */
    public function getSurveyResultSatisfactionRatingsCount($rating)
    {
        $surveyRepository = $this->entityManager->getRepository(SurveyResult::class);

        return $surveyRepository->findBySatisfactionRating($rating);
    }

    /**
     * @param array $surveyData
     *
     * @return \Aws\Result
     */
    public function generateSurveyReports($surveyData)
    {
        $csvHandle = fopen('php://memory', 'r+');

        if (!empty(self::$CSV_COLUMNS)) {
            fputcsv($csvHandle, self::$CSV_COLUMNS);
        }

        $timeStamp = new \DateTime();
        $row['timestamp'] = $timeStamp->format('Y-m-d-H-i-s');
        $row['period'] = 'month';
        $row['slug'] = 'https://mot-testing.i-env.net/';
        $row['rating_1'] = $surveyData['rating_1'];
        $row['rating_2'] = $surveyData['rating_2'];
        $row['rating_3'] = $surveyData['rating_3'];
        $row['rating_4'] = $surveyData['rating_4'];
        $row['rating_5'] = $surveyData['rating_5'];
        $row['total'] = $surveyData['total'];

        fputcsv($csvHandle, $row);
        rewind($csvHandle);

        $result = $this->s3Client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $timeStamp->format('Y-m'),
            'Body' => stream_get_contents($csvHandle),
            'ContentType' => 'text/csv',
        ]);

        fclose($csvHandle);

        return $result;
    }

    /**
     * @return array containing keys 'month', 'size', and 'csv'
     */
    public function getSurveyReports()
    {
        $objects = $this->s3Client->getIterator('ListObjects', [
            'Bucket' => $this->bucket,
        ]);

        $results = [];

        foreach ($objects as $object) {
            $result['month'] = $object['Key'];
            $result['size'] = $object['Size'];
            $result['csv'] = (string) $this->s3Client->getObject([
                'Bucket' => $this->bucket,
                'Key' => $object['Key'],
            ])['Body'];
            $results[] = $result;
        }

        return $results;
    }
}
