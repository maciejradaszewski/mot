<?php
/**
 * This file is part of the DVSA MOT Common project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaCommon\Validation;

use DateTimeImmutable;
use DvsaCommon\Enum\EmergencyReasonCode;
use Zend\Validator\Callback;
use Zend\Validator\Date;
use Zend\Validator\Digits;
use Zend\Validator\StringLength;
use Zend\Validator\ValidatorChain;
use Zend\Validator\ValidatorInterface;

/**
 * ContingencyTest Validator.
 */
class CommonContingencyTestValidator implements GroupValidator
{
    const FIELDSET_SITE              = 'site';
    const FIELDSET_DATE              = 'date';
    const FIELDSET_TIME              = 'time';
    const FIELDSET_REASON            = 'reason';
    const FIELDSET_OTHER_REASON_TEXT = 'otherReasonText';
    const FIELDSET_CONTINGENCY_CODE  = 'contingencyCode';

    /**
     * @var ValidatorInterface[]
     */
    protected $validators;

    /**
     * CommonContingencyTestValidator constructor.
     *
     * @param ValidatorInterface[] $validators
     */
    public function __construct(array $validators = [])
    {
        $validators = array_merge($this->getDefaultValidators(), $validators);

        foreach ($validators as $name => $validator) {
            $this->addValidator($name, $validator);
        }
    }

    /**
     * @param string             $name
     * @param ValidatorInterface $validator
     */
    public function addValidator($name, ValidatorInterface $validator)
    {
        $this->validators[$name] = $validator;
    }

    /**
     * @param $name
     *
     * @return null|ValidatorInterface
     */
    public function getValidator($name)
    {
        return isset($this->validators[$name]) ? $this->validators[$name] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(array $data)
    {
        $messages = [];
        $data = $this->processData($data);

        foreach ($this->validators as $name => $validator) {
            if (false === $validator->isValid($data)) {
                $messages[$name] = $validator->getMessages();
            }
        }

        $valid = empty($messages);

        return new ValidationResult($valid, $messages);
    }

    /**
     * @return ValidatorInterface[]
     */
    protected function getDefaultValidators()
    {
        return [
            self::FIELDSET_SITE              => $this->getSiteValidator(),
            self::FIELDSET_DATE              => $this->getDateValidator(),
            self::FIELDSET_TIME              => $this->getTimeValidator(),
            self::FIELDSET_REASON            => $this->getReasonValidator(),
            self::FIELDSET_OTHER_REASON_TEXT => $this->getOtherReasonTextValidator(),
            self::FIELDSET_CONTINGENCY_CODE  => $this->getContingencyCodeValidator(),
        ];
    }

    /**
     * @return ValidatorChain
     */
    protected function getSiteValidator()
    {
        /*
         * "you must choose a site"
         */
        $site = new Callback(function ($data) {
            if (!isset($data['siteId'])) {
                return false;
            }

            return (new StringLength(['min' => 1]))->isValid($data['siteId']) && (new Digits())->isValid($data['siteId']);
        });
        $site->setMessage('you must choose a site');

        $validatorChain = new ValidatorChain();
        $validatorChain->attach($site, true);

        return $validatorChain;
    }

    /**
     * @return ValidatorInterface
     */
    protected function getDateValidator()
    {
        /*
         * "you must enter a date"
         */
        $dateMustExist = new Callback(function ($data) {
            if (!isset($data['performedAtYear']) || !isset($data['performedAtMonth']) || !isset($data['performedAtDay'])) {
                return false;
            }

            $stringLengthValidator = new StringLength(['min' => 1]);

            return $stringLengthValidator->isValid($data['performedAtYear'])
                && $stringLengthValidator->isValid($data['performedAtMonth'])
                && $stringLengthValidator->isValid($data['performedAtDay']);
        });
        $dateMustExist->setMessage('you must enter a date');

        /*
         * "must be a valid date"
         */
        $validDate = new Callback(function ($data) {
            if (false === (new StringLength(['min' => 4, 'max' => 4]))->isValid($data['performedAtYear'])) {
                return false;
            }

            if (false === (new StringLength(['min' => 2, 'max' => 2]))->isValid($data['performedAtMonth'])) {
                return false;
            }

            if (false === (new StringLength(['min' => 2, 'max' => 2]))->isValid($data['performedAtDay'])) {
                return false;
            }

            return (new Date(['format' => 'Y-m-d']))->isValid(sprintf('%s-%s-%s',
                $data['performedAtYear'], $data['performedAtMonth'], $data['performedAtDay']));
        });
        $validDate->setMessage('must be a valid date');

        /*
         * "must be less than 3 months ago"
         *
         * If the time provided is not valid mark this check as valid (given we have validated the date before).
         */
        $lessThanThreeMonths = new Callback(function ($data) {
            if (!isset($data['performedAtHour'])
                || false === (new StringLength(['min' => 1, 'max' => 2]))->isValid($data['performedAtHour'])) {
                return true;
            }

            if (!isset($data['performedAtMinute'])
                || false === (new StringLength(['min' => 2, 'max' => 2]))->isValid($data['performedAtMinute'])) {
                return true;
            }

            if (!isset($data['performedAtAmPm']) || !in_array($data['performedAtAmPm'], ['am', 'pm'])) {
                return true;
            }

            $threeMonthsAgo = new DateTimeImmutable('-3 months');
            $testDatetime = DateTimeImmutable::createFromFormat('Y-m-d g:ia', sprintf('%s-%s-%s %s:%s%s',
                $data['performedAtYear'], $data['performedAtMonth'], $data['performedAtDay'],
                $data['performedAtHour'], $data['performedAtMinute'], $data['performedAtAmPm']));

            if (!$testDatetime) {
                return false;
            }

            return $testDatetime->getTimestamp() > $threeMonthsAgo->getTimestamp();
        });
        $lessThanThreeMonths->setMessage('must be less than 3 months ago');

        $date = new ValidatorChain();
        $date->attach($dateMustExist, true);
        $date->attach($validDate, true);
        $date->attach($lessThanThreeMonths, true);

        return $date;
    }

    /**
     * @return ValidatorInterface
     */
    protected function getTimeValidator()
    {
        /*
         * "you must enter a time"
         */
        $timeMustExist = new Callback(function ($data) {
            if (!isset($data['performedAtHour']) || !isset($data['performedAtMinute']) || !isset($data['performedAtAmPm'])) {
                return false;
            }

            $stringLengthValidator = new StringLength(['min' => 1]);

            return $stringLengthValidator->isValid($data['performedAtHour'])
            && $stringLengthValidator->isValid($data['performedAtMinute'])
            && $stringLengthValidator->isValid($data['performedAtAmPm']);
        });
        $timeMustExist->setMessage('you must enter a time');

        /*
         * "must be a valid time"
         */
        $validTime = new Callback(function ($data) {
            if (false === (new StringLength(['min' => 1, 'max' => 2]))->isValid($data['performedAtHour'])) {
                return false;
            }

            if (false === (new StringLength(['min' => 2, 'max' => 2]))->isValid($data['performedAtMinute'])) {
                return false;
            }

            if (!isset($data['performedAtAmPm']) || !in_array($data['performedAtAmPm'], ['am', 'pm'])) {
                return false;
            }

            return (new Date(['format' => 'g:ia']))->isValid(sprintf('%s:%s%s',
                $data['performedAtHour'], $data['performedAtMinute'], $data['performedAtAmPm']));

        });
        $validTime->setMessage('must be a valid time');

        $time = new ValidatorChain();
        $time->attach($timeMustExist, true);
        $time->attach($validTime, true);

        return $time;
    }

    /**
     * @return ValidatorInterface
     */
    protected function getReasonValidator()
    {
        /*
         * "you must choose a reason"
         */
        $reason = new Callback(function ($data) {
            if (!isset($data['reasonCode'])) {
                return false;
            }

            if (false === (new StringLength(['min' => 1]))->isValid($data['reasonCode'])) {
                return false;
            }

            if (!in_array($data['reasonCode'], [EmergencyReasonCode::SYSTEM_OUTAGE,
                EmergencyReasonCode::COMMUNICATION_PROBLEM, EmergencyReasonCode::OTHER, ])) {
                return false;
            }

            return true;
        });
        $reason->setMessage('you must choose a reason');

        return $reason;
    }

    /**
     * @return ValidatorInterface
     */
    protected function getOtherReasonTextValidator()
    {
        /*
         * "you must enter a reason"
         */
        $notEmptyCallback = new Callback(function ($data) {
            if (!isset($data['reasonCode']) || $data['reasonCode'] !== EmergencyReasonCode::OTHER) {
                return true;
            }

            if (!isset($data['otherReasonText']) || false === (new StringLength(['min' => 1]))->isValid($data['otherReasonText'])) {
                return false;
            }

            return true;
        });
        $notEmptyCallback->setMessage('you must enter a reason');

        /*
         * "must be longer than 5 characters"
         */
        $minLengthCallback = new Callback(function ($data) {
            if (!isset($data['reasonCode']) || $data['reasonCode'] !== EmergencyReasonCode::OTHER) {
                return true;
            }

            if (!isset($data['otherReasonText']) || false === (new StringLength(['min' => 6]))->isValid($data['otherReasonText'])) {
                return false;
            }

            return true;
        });
        $minLengthCallback->setMessage('must be longer than 5 characters');

        $otherReasonText = new ValidatorChain();
        $otherReasonText->attach($notEmptyCallback, true);
        $otherReasonText->attach($minLengthCallback, true);

        return $otherReasonText;
    }

    /**
     * @return ValidatorChain
     */
    protected function getContingencyCodeValidator()
    {
        /*
         * "you must enter a contingency code"
         */
        $notEmpty = new Callback(function ($data) {
            if (!isset($data['contingencyCode'])) {
                return false;
            }

            return (new StringLength(['min' => 1]))->isValid($data['contingencyCode']);
        });
        $notEmpty->setMessage('you must enter a contingency code');

        $contingencyCode = new ValidatorChain();
        $contingencyCode->attach($notEmpty, true);

        return $contingencyCode;
    }

    /**
     * Converts underscore_keys to camelCaseKeys.
     *
     * @param array $data
     *
     * @return array
     */
    private function processData(array $data)
    {
        $processedData = [];
        $encoding = mb_internal_encoding();

        foreach (array_keys($data) as $k) {
            $normalisedKey = preg_replace('/^[-_]+/', '', trim(lcfirst($k)));

            $normalisedKey = preg_replace_callback(
                '/[-_\s]+(.)?/u',
                function ($match) use ($encoding) {
                    if (isset($match[1])) {
                        return mb_strtoupper($match[1], $encoding);
                    } else {
                        return '';
                    }
                },
                $normalisedKey
            );

            $normalisedKey = preg_replace_callback(
                '/[\d]+(.)?/u',
                function ($match) use ($encoding) {
                    return mb_strtoupper($match[0], $encoding);
                },
                $normalisedKey
            );

            $processedData[$normalisedKey] = $data[$k];
        }

        return $processedData;
    }
}
