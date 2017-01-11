<?php

namespace Account\Validator;

use Account\Controller\ClaimController;
use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use DvsaCommon\Validator\PasswordValidator;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\EmailAddress;
use Zend\Validator\Identical;
use Zend\Validator\Regex;

/**
 *
 */
class ClaimValidator
{
    const MIN_PASSWORD_LENGTH = 8;
    const MAX_PASSWORD_LENGTH = 32;
    const MAX_ANSWER = 70;

    const ERR_MSG_PASSWORD_EMPTY = 'You must enter a password';
    const ERR_MSG_PASSWORD_LENGTH_TOO_SHORT = 'Password must be %s, or more, characters long';
    const ERR_MSG_PASSWORD_LENGTH_TOO_LONG = 'Password must be %s, or less, characters long';
    const ERR_MSG_PASSWORD_FORMAT = 'Password must contain both upper and lower case letters and a digit at least';
    const ERR_MSG_PASSWORD_CONFIRM = 'Provided passwords are not matching';
    const ERR_MSG_PASSWORD_SAME_AS_USERNAME = 'Password must not match your username';

    const ERR_MSG_PASSWORD_CONFIRM_EMPTY = 'You must confirm your password';
    const ERR_MSG_PASSWORD_CONFIRM_MATCH = 'The passwords you have entered don\'t match';

    const ERR_MSG_QUESTION = 'You must choose a question';
    const ERR_MSG_ANSWER_EMPTY = 'You must enter a memorable answer';
    const ERR_MSG_ANSWER_MAX = 'Memorable answer must be less than %s characters long';

    private $isValid;

    private $messages = [];
    private $failedFields = [];
    private $fieldMessages = [];

    public function validateStep($stepName, $data, $forceToResetFlag = false)
    {
        switch ($stepName) {
            case ClaimController::STEP_1_NAME:
                $validationResult = $this->validatePassword($data, $forceToResetFlag);
                break;
            case ClaimController::STEP_2_NAME:
                $validationResult = $this->validateSetSecurityQuestion($data, $forceToResetFlag);
                break;
            case ClaimController::STEP_3_NAME:
                $validationResult = $this->validateGeneratePin($data, $forceToResetFlag);
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Unknown step name "%s".', $stepName));
        }

        return $validationResult;
    }

    public function validatePassword($data, $forceToResetFlag = false)
    {
        $this->setIsValid($forceToResetFlag);

        $firstStepFilter = $this->getFirstStepInputFilter($data);

        if (!$firstStepFilter->isValid()) {
            $this->addMessages($firstStepFilter->getMessages());
            $this->setIsInvalid();
        }

        return $this->isValid();
    }

    public function validateSetSecurityQuestion($data, $forceToResetFlag = false)
    {
        $this->setIsValid($forceToResetFlag);

        $firstStepFilter = $this->getSecondStepInputFilter($data);

        if (!$firstStepFilter->isValid()) {
            $this->addMessages($firstStepFilter->getMessages());
            $this->setIsInvalid();
        }

        return $this->isValid();
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return (bool) $this->isValid;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function getFailedFields()
    {
        return $this->failedFields;
    }

    public function getFieldMessages()
    {
        return $this->fieldMessages;
    }

    public function validateGeneratePin($data)
    {
    }

    private function getFirstStepInputFilter($data)
    {
        $filter = new InputFilter();

        $filter->add(
            [
                'name' => 'password',
                'required' => true,
                'validators' => [
                    [
                        'name' => 'not_empty',
                        'options' => [
                            'message' => self::ERR_MSG_PASSWORD_EMPTY
                        ],
                    ],
                    [
                        'name' => PasswordValidator::class,
                    ],
                    [
                        'name' => 'callback',
                        'options' => [
                            'callback' => function ($value) use ($data) {
                                return $data['username'] !== $value;
                            },
                            'message' => self::ERR_MSG_PASSWORD_SAME_AS_USERNAME
                        ],
                    ],
                ],
            ]
        );

        if (!empty($data['password'])) {
            $filter->add(
                [
                    'name' => 'confirm_password',
                    'required' => true,
                    'validators' => [
                        [
                            'name' => 'not_empty',
                            'options' => [
                                'message' => self::ERR_MSG_PASSWORD_CONFIRM_EMPTY
                            ]
                        ],
                        [
                            'name' => 'identical',
                            'options' => [
                                'token' => 'password',
                                'message' => self::ERR_MSG_PASSWORD_CONFIRM_MATCH
                            ]
                        ],
                    ],
                ]
            );
        }

        $filter->setData($data);

        return $filter;
    }

    private function getSecondStepInputFilter($data)
    {
        $filter = new InputFilter();

        foreach (['a', 'b'] as $letter) {
            $filter->add(
                [
                    'name' => "question_$letter",
                    'required' => true,
                    'validators' => [
                        [
                            'name' => 'not_empty',
                            'options' => [
                                'message' => self::ERR_MSG_QUESTION
                            ]
                        ],
                    ],
                ]
            );

            $filter->add(
                [
                    'name' => "answer_$letter",
                    'required' => true,
                    'validators' => [
                        [
                            'name' => 'not_empty',
                            'options' => [
                                'message' => self::ERR_MSG_ANSWER_EMPTY
                            ]
                        ],
                        [
                            'name' => 'string_length',
                            'options' => [
                                'max' => self::MAX_ANSWER,
                                'message' => sprintf(self::ERR_MSG_ANSWER_MAX, self::MAX_ANSWER)
                            ],
                        ],
                    ],
                ]
            );
        }


        $filter->setData($data);

        return $filter;
    }

    private function addMessages($messages)
    {
        $this->setIsInvalid();
        $this->messages += $messages;
    }

    private function setIsValid($force = false)
    {
        if ($force || is_null($this->isValid)) {
            $this->isValid = true;
        }
    }

    private function setIsInvalid()
    {
        $this->isValid = false;
    }
}
