<?php
namespace DvsaEntities\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaEntities\Entity\PersonSecurityAnswer;
use DvsaEntities\Entity\SecurityQuestion;
use AccountApi\Crypt\SecurityAnswerHashFunction;

/**
 * Class SecurityQuestionRepository.
 */
class SecurityQuestionRepository extends AbstractMutableRepository
{
    /**
     * @return SecurityQuestion[]
     */
    public function findAll()
    {
        $questions = parent::findAll();

        return $questions;
    }

    /**
     * @param array $ids
     *
     * @return array
     */
    public function findAllByIds(array $ids)
    {
        return $this
            ->getEntityManager()
            ->createQuery(sprintf('SELECT sq FROM %s sq INDEX BY sq.id WHERE sq.id IN (:ids)', $this->getEntityName()))
            ->setParameter('ids', $ids)
            ->getResult();
    }

    /**
     * Find a SINGLE matching tuple of (question-id, user-id) and iff there is one, we can
     * then check that the correct answer has been submitted.
     *
     * @param integer $question the database Id of the security question being asked
     * @param integer $userId   the database Id of the user presenting the answer
     * @param string  $answer   the plain text response they gave to the SCSO over the phone
     *
     * @return bool
     */
    public function isAnswerCorrect($question, $userId, $answer)
    {
        /* @var \DvsaEntities\Entity\PersonSecurityAnswer $match */
        $match = $this->_em
            ->getRepository(PersonSecurityAnswer::class)
            ->findOneBy(['person' => $userId, 'securityQuestion' => $question]);

        if ($match !== null) {
            return (new SecurityAnswerHashFunction())
                ->verify(
                    $answer,
                    $match->getAnswer()
                );
        }
        return false;
    }

    /**
     * Find the security question attached to a person
     *
     * @param integer $questionNumber   the zero based question number (0 => question1; 1 => question2)
     * @param integer $userId           the database Id of the user presenting the answer
     *
     * @return SecurityQuestion
     * @throws NotFoundException
     */
    public function findQuestionByQuestionNumber($questionNumber, $userId)
    {
        /* @var \DvsaEntities\Entity\PersonSecurityAnswer[] $results */
        $results = $this->_em
            ->getRepository(PersonSecurityAnswer::class)
            ->findBy(['person' => $userId], ['id' => 'ASC'], 1, $questionNumber);

        if (count($results) !== 1) {
            throw new NotFoundException('Question not found');
        }
        return $results[0]->getSecurityQuestion();
    }
}
