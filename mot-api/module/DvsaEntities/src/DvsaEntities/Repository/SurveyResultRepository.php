<?php

namespace DvsaEntities\Repository;

use DateTime;

/**
 * Class SurveyResultRepository.
 *
 * @ORM\Entity(repositoryClass="DvsaEntities\Repository\SurveyResult", readOnly=true)
 */
class SurveyResultRepository extends AbstractMutableRepository
{
    /**
     * Returns the number of survey results with a particular rating for the last calendar month.
     *
     * @param $satisfactionRating
     *
     * @return mixed
     */
    public function findBySatisfactionRating($satisfactionRating)
    {
        $now = new DateTime();
        $thirtyDaysAgo = $now->sub(new \DateInterval('P1M'));
        $queryBuilder = $this->createQueryBuilder('sr');

        return $queryBuilder
                ->select('e')
                ->from($this->getEntityName(), 'e')
                ->where('e.createdOn > :from')
                ->andWhere('e.satisfactionRating = :rating')
                ->setParameters([
                    'from' => $thirtyDaysAgo,
                    'rating' => $satisfactionRating,
                ])->getQuery()->execute();
    }
}
