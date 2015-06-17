<?php

namespace TestSupport\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use DvsaCommon\Utility\ArrayUtils;
use TestSupport\Helper\SecurityAnswerHash;
use TestSupport\Helper\TestDataResponseHelper;
use Zend\Mvc\Controller\AbstractRestfulController;

/**
 * Vehicle related methods
 *
 * Should not be deployed in production.
 */
class SecurityQuestionDataController extends BaseTestSupportRestfulController
{
    const PERSON_ID = 'person';
    const QUESTION  = 'question';
    const ANSWER    = 'answer';

    public function create($data)
    {
        $person     = ArrayUtils::tryGet($data, self::PERSON_ID, 1);
        $question   = ArrayUtils::tryGet($data, self::QUESTION, 1);
        $answer     = ArrayUtils::tryGet($data, self::ANSWER, 'Blah');

        $answer = (new SecurityAnswerHash())->hash($answer);

        /** @var EntityManager $entityManager */
        $entityManager = $this->getServiceLocator()->get(EntityManager::class);
        /** @var Connection $connection */
        $connection = $entityManager->getConnection();

        $connection->transactional(
            function () use ($person, $answer, $question, $connection) {
                $connection->executeQuery(
                    'INSERT INTO person_security_question_map(person_id, security_question_id, answer, created_by)
                      VALUE(:person_id, :security_question_id, :answer, :created_by)',
                    [
                        'person_id'             => $person,
                        'security_question_id'  => $question,
                        'answer'                => $answer,
                        'created_by'            => 1,
                    ]
                );
            }
        );

        return TestDataResponseHelper::jsonOk('Success');
    }
}
