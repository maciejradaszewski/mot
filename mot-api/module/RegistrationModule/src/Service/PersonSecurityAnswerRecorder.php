<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Api\RegistrationModule\Service;

use AccountApi\Crypt\SecurityAnswerHashFunction;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\PersonSecurityAnswer;
use DvsaEntities\Entity\SecurityQuestion;
use DvsaEntities\Repository\SecurityQuestionRepository;

/**
 * Class PersonSecurityAnswerRecorder.
 */
class PersonSecurityAnswerRecorder
{
    /**
     * @var SecurityQuestionRepository
     */
    private $securityQuestionRepository;

    /**
     * @var SecurityAnswerHashFunction
     */
    private $securityAnswerHashFunction;

    public function __construct(
        SecurityQuestionRepository $securityQuestionRepository,
        SecurityAnswerHashFunction $securityAnswerHashFunction
    ) {
        $this->securityQuestionRepository = $securityQuestionRepository;
        $this->securityAnswerHashFunction = $securityAnswerHashFunction;
    }

    /**
     * @param Person $person
     * @param int    $securityQuestionId
     * @param string $answer
     *
     * @return PersonSecurityAnswer
     */
    public function create(Person $person, $securityQuestionId, $answer)
    {
        /** @var SecurityQuestion $securityQuestion */
        $securityQuestion = $this->securityQuestionRepository->find($securityQuestionId);

        $personSecurityAnswer = new PersonSecurityAnswer(
            $securityQuestion,
            $person,
            $this->securityAnswerHashFunction->hash($answer)
        );

        return $personSecurityAnswer;
    }
}
