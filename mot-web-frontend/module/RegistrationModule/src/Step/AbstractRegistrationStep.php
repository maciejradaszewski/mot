<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\RegistrationModule\Step;

use Dvsa\Mot\Frontend\RegistrationModule\Service\RegistrationSessionService;
use DvsaCommon\InputFilter\Registration\AddressInputFilter;
use DvsaCommon\InputFilter\Registration\DetailsInputFilter;
use DvsaCommon\InputFilter\Registration\SecurityQuestionFirstInputFilter;
use DvsaCommon\InputFilter\Registration\SecurityQuestionSecondInputFilter;
use Zend\InputFilter\InputFilter;

/**
 * Base class for RegistrationSteps.
 */
abstract class AbstractRegistrationStep implements RegistrationStep
{
    /**
     * @var RegistrationSessionService
     */
    protected $sessionService;

    /**
     * @var InputFilter
     */
    protected $filter;

    /**
     * @param RegistrationSessionService $sessionService
     */
    public function __construct(RegistrationSessionService $sessionService, InputFilter $filter)
    {
        $this->sessionService = $sessionService;
        $this->filter = $filter;
    }

    /**
     * @param array $values
     *
     * @return bool
     */
    public function validate(array $values = [])
    {
        $values = count($values) ? $values : $this->toArray();
        $values = $this->clean($values);
        $this->filter->init();
        $this->filter->setData($values);

        return $this->filter->isValid();
    }

    /**
     * The route for this step.
     *
     * @return mixed
     */
    abstract public function route();

    /**
     * list of fields which must be filtered by the clean function to remove whitespace.
     *
     * @return array
     */
    abstract protected function getCleanFilterWhiteList();

    /**
     * Load the steps data from the session storage.
     *
     * @return $this
     */
    abstract public function load();

    /**
     * Save the steps data to the session storage.
     *
     * @return bool
     */
    public function save()
    {
        // Cache the results because isValid performs the full validation on each call.
        $isValid = $this->filter->isValid();
        if (true === $isValid) {
            $this->saveToSession();
        }

        return $isValid;
    }

    /**
     * Export the step values as an array.
     *
     * @return array
     */
    abstract public function toArray();

    /**
     * @return array
     */
    public function toViewArray()
    {
        $values = $this->toArray();

        $result = array_merge([], $values);
        $result['errors'] = $this->filter->getMessages();
        $result['errorsSummary'] = $this->getErrorsSummary();
        $result['isValid'] = count($result['errors']) === 0;

        // Automatically make error check variables available to the views.
        // This removes the need for Array::get() checks inside the views, and makes them safer and cleaner.
        foreach ($values as $fieldName => $value) {
            $result[$fieldName . 'HasError'] = isset($result['errors'][$fieldName]);
        }

        return $result;
    }

    /**
     * @param array $values
     *
     * @return mixed
     */
    abstract public function readFromArray(array $values);

    /**
     * describes the steps progress in the registration process.
     *
     * Step 1 of 6
     * Step 2 of 6
     * etc
     *
     * @return string|null
     */
    public function getProgress()
    {
        return;
    }

    /**
     * Clean whitespace before and after value.
     *
     * @param array $values
     *
     * @return array
     */
    public function clean(array $values)
    {
        $array = [];
        $blacklist = array_flip($this->getCleanFilterWhiteList());

        // if black list is empty no need to whitelist anything.
        if (empty($blacklist)) {
            return $values;
        }

        foreach ($values as $key => $value) {
            if (array_key_exists($key, $blacklist)) {
                $array[$key] = trim($value);
            } else {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    /**
     * A function that must be implemented in the step. This is how
     * we enforce that the logic of what is saved within a step, stays within that step.
     *
     * @return bool
     */
    protected function saveToSession()
    {
        return $this->sessionService->save(static::STEP_ID, $this->clean($this->toArray()));
    }

    /**
     * Preparing validation messages with expected format for summary boxes.
     *
     * @return array
     */
    private function getErrorsSummary()
    {
        $genericErrors = $this->filter->getMessages();
        $errorsSummary = [];

        foreach ($genericErrors as $fieldName => $messages) {
            $errorsSummary[$fieldName] = [];

            foreach ($messages as $validator => $message) {
                $errorsSummary[$fieldName][$validator] = $this->prependLabel($fieldName, $message);
            }
        }

        return $errorsSummary;
    }

    /**
     * @param string $fieldName the field name known to the filter input
     * @param string $message   the original validation message provided by the filter input
     *
     * @return string expected format which is prepended by the input's label (html DOM)
     */
    private function prependLabel($fieldName, $message)
    {
        return $this->getFieldLabel($fieldName) . ' - ' . $message;
    }

    /**
     * To guess or map the label's value (name)
     * Since we have decided to not follow the provided UX-guide and also renamed some of our centralised
     * - field names located in our filterInputs, we have no choice but mapping those incorrect names here :).
     *
     * @param string $fieldName
     *
     * @return string
     */
    private function getFieldLabel($fieldName)
    {
        $uglyMap = [
            DetailsInputFilter::FIELD_EMAIL_CONFIRM           => 'Re-type your email address',
            AddressInputFilter::FIELD_ADDRESS_1               => 'Address line 1',
            AddressInputFilter::FIELD_ADDRESS_2               => 'Address line 2',
            AddressInputFilter::FIELD_ADDRESS_3               => 'Address line 3',
            SecurityQuestionFirstInputFilter::FIELD_QUESTION  => 'Select a question to answer',
            SecurityQuestionFirstInputFilter::FIELD_ANSWER    => 'Your answer',
            SecurityQuestionSecondInputFilter::FIELD_QUESTION => 'Select a question to answer',
            SecurityQuestionSecondInputFilter::FIELD_ANSWER   => 'Your answer',
        ];

        if (array_key_exists($fieldName, $uglyMap)) {
            return $uglyMap[$fieldName];
        }

        return ucfirst(strtolower(preg_replace('/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]/', ' $0', $fieldName)));
    }
}
