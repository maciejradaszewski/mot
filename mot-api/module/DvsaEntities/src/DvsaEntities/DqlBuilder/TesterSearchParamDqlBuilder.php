<?php

namespace DvsaEntities\DqlBuilder;

use DvsaCommon\Enum\AuthorisationForTestingMotStatusCode;

/**
 * Class TesterSearchParamDqlBuilder
 * @package DvsaEntities\DqlBuilder
 */
class TesterSearchParamDqlBuilder extends SearchParamDqlBuilder
{
    const SORT_BY_ID = 0;
    const SORT_BY_USERNAME = 1;

    protected $orderByTable = 'tester';
    protected $searchWords = [];

    /**
     * Provides an opportunity to initialize and values before processing
     *
     * @return $this
     */
    public function initialize()
    {
        $search = trim($this->params->getSearch());
        $this->searchWords = strlen($search) > 0 ? explode(" ", strtoupper($search)) : null;
        // todo: make this the default action in the child class.
        return $this;
    }

    /**
     * Build the Dql from the params
     *
     * @param bool $totalCount
     *
     * @return mixed|void
     */
    protected function buildDql($totalCount = false)
    {
        $dql         = [];
        $filters     = [];

        $dql[] = $this->generateDqlHeader($totalCount);

        if ($this->searchWords) {
            $this->addFiltersByValues($filters, $this->searchWords, "tester.username LIKE :TESTER_USERNAME", "OR");
            $this->addFiltersByValues($filters, $this->searchWords, "tester.firstName LIKE :TESTER_FIRST_NAME", "OR");
            $this->addFiltersByValues($filters, $this->searchWords, "tester.middleName LIKE :TESTER_MIDDLE_NAME", "OR");
            $this->addFiltersByValues($filters, $this->searchWords, "tester.familyName LIKE :TESTER_SURNAME", "OR");
        }

        $dql[] = count($filters) ? join(' OR ', $filters): '1';

        $this->generateDqlFooter($totalCount, $dql);
    }

    /**
     * Build the Query and add any parameters
     *
     * @param bool $totalCount
     *
     * @return mixed|void
     */
    protected function buildQuery($totalCount = false)
    {
        $query = $this->createQuery($totalCount);

        if (strlen($this->params->getSearch())) {
            $this->addParametersByValues($query, $this->searchWords, 'TESTER_USERNAME', '%%%s%%');
            $this->addParametersByValues($query, $this->searchWords, 'TESTER_FIRST_NAME', '%%%s%%');
            $this->addParametersByValues($query, $this->searchWords, 'TESTER_MIDDLE_NAME', '%%%s%%');
            $this->addParametersByValues($query, $this->searchWords, 'TESTER_SURNAME', '%%%s%%');
        }
        $query->setParameter(
            'statuses', [
                AuthorisationForTestingMotStatusCode::DEMO_TEST_NEEDED,
                AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED,
                AuthorisationForTestingMotStatusCode::QUALIFIED,
                AuthorisationForTestingMotStatusCode::REFRESHER_NEEDED,
                AuthorisationForTestingMotStatusCode::SUSPENDED
            ]
        );

        $this->assignQuery($totalCount, $query);
    }

    /**
     * Build the correct DQL header depending on the current settings.
     *
     * @param $totalCount
     *
     * @return string
     */
    protected function generateDqlHeader($totalCount)
    {
        $select = $totalCount ? 'count(DISTINCT tester)' : ' DISTINCT tester';

        return 'SELECT ' . $select . ' from DvsaEntities\Entity\Person tester
                    WHERE EXISTS (SELECT 1 FROM DvsaEntities\Entity\AuthorisationForTestingMot auth,
                        DvsaEntities\Entity\AuthorisationForTestingMotStatus status
                        WHERE auth.person = tester.id
                            AND auth.status = status.id
                            AND status.code IN (:statuses)
                    ) AND ';
    }

    /**
     * Build the correct DQL footer depending on the current settings.
     *
     * @param $totalCount
     * @param $dql
     */
    protected function generateDqlFooter($totalCount, $dql)
    {
        if ($totalCount) {
            $this->searchCountDql = join(" ", $dql);
        } else {
            $dql[] = "ORDER BY {$this->getOrderByTable()}.{$this->params->getSortColumnName()} " .
                $this->params->getSortDirection();
            $this->searchDql = join(" ", $dql);
        }
    }

    /**
     * Returns the current table to order by. Useful in multi join searches
     *
     * @return string
     */
    public function getOrderByTable()
    {
        return $this->orderByTable;
    }
}
