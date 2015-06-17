<?php

namespace AccountApi\Service\Validator;

use DvsaCommon\Validator\PasswordValidator;
use DvsaCommonApi\Service\Exception\RequiredFieldException;
use DvsaCommonApi\Service\Validator\AbstractValidator;
use DvsaEntities\Repository\SecurityQuestionRepository;
use AccountApi\Service\SecurityQuestionService;

/**
 * Claim Account Validator.
 */
class ClaimValidator extends AbstractValidator
{
    /**
     * @var array
     */
    private $requiredFields = [
        'emailOptOut',
        'password',
        'passwordConfirmation',
        'securityQuestionOneId',
        'securityAnswerOne',
        'securityQuestionTwoId',
        'securityAnswerTwo',
        'personId'
    ];

    /**
     * @var \DvsaEntities\Repository\SecurityQuestionRepository
     */
    private $securityQuestionRepository;

    /**
     * @var PasswordValidator
     */
    private $passwordValidator;

    /**
     * @var \AccountApi\Service\SecurityQuestionService
     */
    protected $securityQuestionService;

    const ERROR_EMAIL_INCORRECT_FORMAT = "Incorrect email address format";
    const ERROR_EMAIL_CONFIRMATION = "Emails do not match";
    const ERROR_PASSWORD_CONFIRMATION = "Passwords do not match";
    const ERROR_PIN_INCORRECT_FORMAT = "PIN - must be a 6 digit number";
    const ERROR_SECURITY_QUESTION_NOT_EXIST = "Security Question - The security question received does not exist";

    public function __construct(
        SecurityQuestionService $securityQuestionService,
        SecurityQuestionRepository $securityQuestionRepository
    ) {
        $this->securityQuestionService    = $securityQuestionService;
        $this->securityQuestionRepository = $securityQuestionRepository;
        $this->passwordValidator          = new PasswordValidator();

        parent::__construct();
    }

    public function validate($data = [])
    {
        $isEmailRequired = true;
        if (isset($data['emailOptOut']) && $data['emailOptOut'] === true) {
            $isEmailRequired = false;
        }

        if ($isEmailRequired && isset($data['email'])) {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->errors->add(self::ERROR_EMAIL_INCORRECT_FORMAT, 'email');
            }

            if (array_key_exists('emailConfirmation', $data)) {
                if (trim(mb_strtoupper($data['email'])) !== trim(mb_strtoupper($data['emailConfirmation']))) {
                    $this->errors->add(self::ERROR_EMAIL_CONFIRMATION, 'emailConfirmation');
                }
            }
        }

        RequiredFieldException::CheckIfRequiredFieldsNotEmpty($this->requiredFields, $data);

        if ($data['password'] !== $data['passwordConfirmation']) {
            $this->errors->add(self::ERROR_PASSWORD_CONFIRMATION, 'passwordConfirmation');
        }

        if (!$this->passwordValidator->isValid($data['password'])) {
            foreach ($this->passwordValidator->getMessages() as $message) {
                $this->errors->add($message, 'password');
            }
        }

        $this->errors->throwIfAny();
    }

    /**
     * @param array $data
     *
     * @throws \DvsaCommonApi\Service\Exception\BadRequestException
     *
     * @return \DvsaEntities\Entity\SecurityQuestion[]
     */
    public function validateSecurityQuestions($data)
    {
        $securityQuestions = $this
            ->securityQuestionRepository
            ->findAllByIds([$data['securityQuestionOneId'], $data['securityQuestionTwoId']]);

        if (!isset($securityQuestions[$data['securityQuestionOneId']])) {
            $this->errors->add(self::ERROR_SECURITY_QUESTION_NOT_EXIST, 'securityQuestionOneId');
        }

        if (!isset($securityQuestions[$data['securityQuestionTwoId']])) {
            $this->errors->add(self::ERROR_SECURITY_QUESTION_NOT_EXIST, 'securityQuestionTwoId');
        }

        $this->errors->throwIfAny();

        return $securityQuestions;
    }
}
