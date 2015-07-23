<?php

namespace DvsaMotApi\Controller\Validator;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Enum\EmergencyReasonCode;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonApi\Error\Message as ErrorMessage;
use DvsaCommonApi\Service\Exception\BadRequestException;
use DvsaEntities\Entity\EmergencyLog;
use DvsaMotApi\Service\EmergencyService;
use SiteApi\Service\SiteService;
use PersonApi\Service\PersonService;
use Zend\ServiceManager\ServiceManager;

/**
 * Class EmergencyLogValidator
 *
 * This class will perform validation on submitted data that is to be
 * written into the emergency log (contingency testing) system. It
 * allows data to be supplied on construction for instant validation
 * or data can be validated at a later stage if required.
 *
 * @package DvsaMotApi\Controller\Validator
 */
class EmergencyLogValidator
{
    const S_UNDEFINED = 0;
    const S_VALID = 1;
    const S_INVALID = -1;

    const TESTER_MAX_LENGTH = 8;

    const MIN_REASON_TEXT_LENGTH   = 5;
    const ERR_REASON_LENGTH        = 'Reason text is too short: minimum is 5 characters.';
    const ERR_REASON_INVALID       = 'Please select a valid reason code ';
    const ERR_REASON_TEXT_INVALID  = 'Please enter a reason for the contingency test';
    const ERR_REASON_XSS           = 'Reason text cannot may not contain Javascript';
    const ERR_TESTER_TOO_LONG      = 'Tester code should be 8 characters';
    const ERR_TESTER_INVALID       = 'Please enter the code of a valid tester';
    const ERR_DATE_REQUIRED        = 'Please supply a valid test date';
    const ERR_DATE_INVALID         = 'Test date must be less than three months ago';
    const ERR_CODE_REQUIRED        = 'Please supply an contingency code';
    const ERR_CODE_INVALID         = 'Please use a valid contingency code';
    const ERR_SITE_REQUIRED        = 'Please supply a site ID';
    const ERR_SITE_INVALID         = 'Please enter a valid site ID';
    const ERR_TEST_TYPE_REQUIRED   = 'Please supply a test type';
    const ERR_TEST_TYPE_INVALID    = 'Please select a valid test type';
    const ERR_TESTER_CODE_REQUIRED = 'Please supply a tested by whom value';
    const ERR_TESTER_CODE_INVALID  = 'Please select a valid tested by whom value';

    const DATE_BEFORE               = '01-01-1900';

    /** Indicates if the validation has been performed */
    protected $state;

    /** The data to be subject to the validation process */
    protected $data;

    /** The service locator instance for validation */
    protected $serviceManager;

    /** The last known errors that stopped validation  */
    protected $errorMsg;

    /** @var EmergencyLog The last successfully retrieved emergencylog entity */
    protected $emergencyLog;

    public static $mustHaveFieldNames
        = [
            'contingency_code' => ['is_string', 'isValidEmergencyLogCode'],
            'tested_by_whom'   => ['is_string', 'isValidTestedByWhom'],
            'site_id'          => ['is_numeric', 'isValidSite'],
            'test_type'        => ['is_string', 'isValidTestType'],
            'tester_code'      => ['is_string', 'isValidTesterCode'],
            'reason_code'      => ['is_string', 'isValidReasonCode'],
            'test_date'        => ['isValidTestDate'],
            'test_date_year'   => ['isValidYear']
        ];

    /**
     * Can take the data now or later.
     *
     * @param $sm   ServiceManager for performing various checks
     * @param $data Array can be null indicating delayed validation
     */
    public function __construct(ServiceManager $sm, Array $data = null)
    {
        $this->errorMsg = [];
        $this->state = self::S_UNDEFINED;
        $this->serviceManager = $sm;

        if ($data && $sm) {
            $this->validate($data);
        }
    }

    /**
     * @return bool TRUE of the validator has been executed.
     */
    public function isValidated()
    {
        return self::S_UNDEFINED != $this->state;
    }

    /**
     * @return bool TRUE iff validation passed.
     */
    public function isValid()
    {
        return self::S_VALID == $this->state;
    }

    /**
     * Returns the last known error messages that stopped validation.
     *
     * @return Array
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    /**
     * Validates the given data set against the internal rules for an
     * emergency log request.
     *
     * @param $data  Array contains data for validation
     *
     * @throws \Exception
     * @return bool
     */
    public function validate(Array $data)
    {
        $this->state = self::S_INVALID;
        $this->data = $data;

        foreach (self::$mustHaveFieldNames as $field => $predicateList) {
            foreach ($predicateList as $predicate) {
                $value = ArrayUtils::tryGet($data, $field, null);

                if (method_exists($this, $predicate)) {
                    $this->$predicate($value, $data);
                } else {
                    $predicate($value);
                }
            }
        }

        $this->data = null;

        if (0 == count($this->errorMsg)) {
            $this->data = $data;
            $this->state = self::S_VALID;
        }
        return $this->isValid();
    }


    /**
     * Maintains a list of error messages raised during validation
     *
     * @param $text String
     */
    protected function addErrorMsg($text)
    {
        $this->errorMsg[] = new ErrorMessage(
            $text,
            BadRequestException::ERROR_CODE_INVALID_DATA,
            $text
        );
    }

    /**
     * Answers the current internal validation state
     *
     * @return bool
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Attempts to load the entity with the given value which is taken as an indication
     * that the given code is valid.
     *
     * @param $value
     */
    protected function isValidEmergencyLogCode($value)
    {
        $this->emergencyLog = null;

        /** @var EmergencyService $emService */
        $emService = $this->serviceManager->get(EmergencyService::class);

        if (!$log = $emService->getEmergencyLog($value)) {
            $this->addErrorMsg(self::ERR_CODE_INVALID);
        }
        $this->emergencyLog = $log;
    }

    /**
     * Ensures a tester ID is valid: must return a real Person and that person MUST be a tester.
     *
     * We first locate a Person by the given user reference, if that fails we bail. If we do
     * locate a person we can then use the tester service to ensure they are a valid tester.
     *
     * @param $value String an alphanumeric user reference string
     */
    protected function isValidTestedByWhom($value)
    {
        if (!empty($value)) {
            switch (strtolower($value)) {
                case 'current':
                case 'other':
                    break;
                default:
                    $this->addErrorMsg(self::ERR_TESTER_CODE_INVALID);
                    break;
            }
        } else {
            $this->addErrorMsg(self::ERR_TESTER_CODE_REQUIRED);
        }
    }

    /**
     * Ensures a site ID is valid: must return a real Site.
     *
     * @param $value
     */
    protected function isValidSite($value)
    {
        if (!empty($value)) {
            try {
                $this->serviceManager->get(SiteService::class)->getSiteData($value);
            } catch (\Exception $e) {
                $this->addErrorMsg(self::ERR_SITE_INVALID);
            }
        } else {
            $this->addErrorMsg(self::ERR_SITE_REQUIRED);
        }
    }

    /**
     * Check the reason text is valid when the reason code is chosen to be 'OT', i.e. Other
     *
     * @param $value String current value from data array
     * @param $data  Array from the submission request
     */
    protected function isValidReasonCode($value, $data)
    {
        if (EmergencyReasonCode::exists($value)) {
            if (EmergencyReasonCode::OTHER === $value) {
                $reasonText = ArrayUtils::tryGet($data, 'reason_text', '');

                if (strlen($reasonText) < self::MIN_REASON_TEXT_LENGTH) {
                    $this->addErrorMsg(self::ERR_REASON_LENGTH);
                }

                if (preg_match('/javascript:/is', $reasonText)
                || preg_match('/\<script\>/is', $reasonText)
                ) {
                    $this->addErrorMsg(self::ERR_REASON_XSS);
                }
            }
        } else {
            $this->addErrorMsg(self::ERR_REASON_INVALID);
        }
    }

    /**
     * Checks the re-test type for being a recognised value.
     *
     * @param $value \DateTime the submitted for value
     */
    protected function isValidTestType($value)
    {
        if (!empty($value)) {
            switch (strtolower($value)) {
                case 'normal':
                case 'retest':
                    break;
                default:
                    $this->addErrorMsg(self::ERR_TEST_TYPE_INVALID);
                    break;
            }
        } else {
            $this->addErrorMsg(self::ERR_TEST_TYPE_REQUIRED);
        }
    }

    /**
     * Checks the tester code for being a recognised value.
     *
     * @param $value String The code of the tester
     */
    protected function isValidTesterCode($value)
    {
        if (ArrayUtils::tryGet($this->data, 'tested_by_whom') != 'other') {
            return;
        }

        if (!empty($value)) {
            if (strlen($value) === self::TESTER_MAX_LENGTH) {
                $isValid = false;
                try {
                    /** @var PersonService $personService */
                    $personService = $this->serviceManager->get(PersonService::class);
                    $person = $personService->getPersonByIdentifier($value);

                    if ($person) {
                        $isValid = $person->isQualifiedTester();
                    }
                } catch (\Exception $e) {
                    $isValid = false;
                }
                if (!$isValid) {
                    $this->addErrorMsg(self::ERR_TESTER_INVALID);
                }
            } else {
                $this->addErrorMsg(self::ERR_TESTER_TOO_LONG);
            }
        } else {
            $this->addErrorMsg(self::ERR_TESTER_INVALID);
        }
    }

    /**
     * @param $value \DateTime contains the Y-m-d of the test
     */
    protected function isValidTestDate($value)
    {
        if (!empty($value)) {
            if (!checkdate(
                $this->data['test_date_month'],
                $this->data['test_date_day'],
                $this->data['test_date_year']
            )) {
                $this->addErrorMsg(self::ERR_DATE_REQUIRED);
            } else {
                if (DateUtils::isDateInFuture($value)) {
                    $this->addErrorMsg(self::ERR_DATE_INVALID);
                } elseif ($value < DateUtils::subtractCalendarMonths(DateUtils::today(), 3)) {
                    $this->addErrorMsg(
                        sprintf(self::ERR_DATE_INVALID, $value)
                    );
                }
            }
        } else {
            $this->addErrorMsg(self::ERR_DATE_REQUIRED);
        }
    }

    protected function isValidYear($year)
    {
        if (is_string($year) && 4 !== strlen($year)) {
            $this->addErrorMsg('Year should be 4 characters');
        }
    }

    /**
     * Get the last validated emergencyLog entity
     *
     * @return EmergencyLog The emergency log
     */
    public function getEmergencyLog()
    {
        return $this->emergencyLog;
    }
}
