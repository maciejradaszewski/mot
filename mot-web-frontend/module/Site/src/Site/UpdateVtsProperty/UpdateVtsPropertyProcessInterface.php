<?php

namespace Site\UpdateVtsProperty;

use DvsaCommon\HttpRestJson\Exception\ValidationException;
use Zend\Form\Form;

interface UpdateVtsPropertyProcessInterface
{
    /**
     * Name of the property that will be changed
     *
     * @return string
     */
    public function getPropertyName();

    /**
     * If true, then the user should be taken through additional step to confirm entered values.
     *
     * @return bool
     */
    public function getRequiresReview();

    /**
     * What should be displayed on the submit button control.
     *
     * @return string
     */
    public function getSubmitButtonText();

    /**
     * Partial file that will display the form.
     *
     * @return string
     */
    public function getFormPartial();

    /**
     * Zend form used to edit values
     *
     * @return Form
     */
    public function createEmptyForm();

    /**
     * Gets the values that the form should be pre-populated with.
     * (e.g. old values)
     * @param $vtsId
     *
     * @return array
     */
    public function getPrePopulatedData($vtsId);

    /**
     * The permission that is required to edit the property
     *
     * @return string
     */
    public function getPermission();

    /**
     * Will make a call to API to update the data from the form
     *
     * @param $vtsId
     * @param $formData
     * @return void
     *
     * @throws ValidationException
     */
    public function update($vtsId, $formData);

    /**
     * Tells what message should be shown to the user when the form has been successfully submitted
     *
     * @return string
     */
    public function getSuccessfulEditMessage();

    /**
     * The title that will be displayed on the form page
     *
     * @return string
     */
    public function getFormPageTitle();
}
