<?php

namespace AccountApi\Service;

use AccountApi\Mapper\SecurityQuestionMapper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use DvsaCommon\Dto\Security\SecurityQuestionDto;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommonApi\Service\EntityFinderTrait;
use DvsaEntities\Repository\SecurityQuestionRepository;

/**
 * Class SecurityQuestionService
 * @package UserApi\SecurityQuestion\Service
 */
class SecurityQuestionService
{
    use EntityFinderTrait;

    const ERR_TYPE_INVALID = 'Incorrect argument types, expected: int, string';

    /** @var SecurityQuestionRepository */
    private $securityQuestionRepository;
    /** @var SecurityQuestionMapper */
    private $mapper;
    /** @var ParamObfuscator  */
    private $obfuscator;

    /**
     * @param SecurityQuestionRepository $securityQuestionRepository
     * @param SecurityQuestionMapper     $mapper
     * @param ParamObfuscator            $obfuscator
     */
    public function __construct(
        SecurityQuestionRepository $securityQuestionRepository,
        SecurityQuestionMapper $mapper,
        ParamObfuscator $obfuscator
    ) {
        $this->securityQuestionRepository = $securityQuestionRepository;
        $this->mapper = $mapper;
        $this->obfuscator = $obfuscator;
    }

    /**
     *
     * @return \DvsaCommon\Dto\Security\SecurityQuestionDto[]
     */
    public function getAll()
    {
        $questions = $this->securityQuestionRepository->findAll();
        return $this->mapper->manyToDto($questions);
    }

    /**
     * @param integer $questionId      is the database id of the security question to be tested with an answer.
     * @param integer $userId          the database id of the user on the phone
     * @param string  $answer contains the candidate answer as submitted.
     *
     * @return true or false, true meaning the answer is deemed correct.
     * @throws \Exception when required for duff data etc.
     */
    public function isAnswerCorrect($questionId, $userId, $answer)
    {
        if (is_integer($questionId) && is_integer($userId) && is_string($answer)) {
            return $this->securityQuestionRepository->isAnswerCorrect($questionId, $userId, $answer);
        }

        throw new \Exception(self::ERR_TYPE_INVALID);
    }

    /**
     * @param int $questionNumber   The zero based question number (0 => question1; 1 => question2)
     * @param int $userId           integer the database id of the user on the phone
     *
     * @return SecurityQuestionDto The security question of a person.
     * @throws \Exception when required for duff data etc.
     */
    public function findQuestionByQuestionNumber($questionNumber, $userId)
    {
        if (is_integer($questionNumber) && is_integer($userId)) {
            return $this->mapper->toDto(
                $this->securityQuestionRepository
                    ->findQuestionByQuestionNumber(
                        $questionNumber,
                        $userId
                    )
            );
        }

        throw new \Exception(self::ERR_TYPE_INVALID);
    }
}
