<?php

namespace DvsaMotApi\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommonApi\Service\AbstractService;
use DvsaEntities\Entity\SurveyResult;

/**
 * Class SurveyService
 * @package DvsaMotApi\Service
 */
class SurveyService extends AbstractService
{
    /**
     * SurveyService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    /**
     * @param array $data
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
} 