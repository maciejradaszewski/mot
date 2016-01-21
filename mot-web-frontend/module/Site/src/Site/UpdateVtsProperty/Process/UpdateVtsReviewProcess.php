<?php

namespace Site\UpdateVtsProperty\Process;

use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use Site\UpdateVtsProperty\UpdateVtsReviewProcessInterface;
use Zend\Form\Form;

class UpdateVtsReviewProcess implements UpdateVtsReviewProcessInterface, AutoWireableInterface
{
    private $formToGdsTableTransformer;
    private $reviewPageTitle;
    private $reviewPageLede;
    private $reviewPageButtonText;
    private $propertyName;
    private $permission;
    private $requiresReview;
    private $form;
    private $submitButtonText;
    private $formPartial;
    private $currentValuesExtractor;
    private $valueUpdater;
    private $successfulEditMessage;
    private $formPageTitle;
    private $breadCrumb;

    public function __construct(
        $propertyName,
        $permission,
        $requiresReview,
        $formPartial,
        Form $form,
        $submitButtonText,
        $currentValuesExtractor,
        $valueUpdater,
        $successfulEditMessage,
        $formPageTitle,
        $formToGdsTransformer,
        $reviewPageTitle,
        $reviewPageLede,
        $reviewPageButtonText,
        $breadCrumb
    )
    {
        $this->formToGdsTableTransformer = $formToGdsTransformer;
        $this->reviewPageTitle = $reviewPageTitle;
        $this->reviewPageLede = $reviewPageLede;
        $this->reviewPageButtonText = $reviewPageButtonText;
        $this->propertyName = $propertyName;
        $this->permission = $permission;
        $this->requiresReview = $requiresReview;
        $this->form = $form;
        $this->submitButtonText = $submitButtonText;
        $this->formPartial = $formPartial;
        $this->currentValuesExtractor = $currentValuesExtractor;
        $this->valueUpdater = $valueUpdater;
        $this->successfulEditMessage = $successfulEditMessage;
        $this->formPageTitle = $formPageTitle;
        $this->breadCrumb = $breadCrumb;
    }

    public function getPropertyName()
    {
        return $this->propertyName;
    }

    public function getRequiresReview()
    {
        return $this->requiresReview;
    }

    public function getFormPartial()
    {
        return $this->formPartial;
    }

    public function createEmptyForm()
    {
        return $this->form;
    }

    public function getSubmitButtonText()
    {
        return $this->submitButtonText;
    }

    public function getPrePopulatedData($vtsId)
    {
        $function = $this->currentValuesExtractor;
        return $function($vtsId);
    }

    public function getPermission()
    {
        return $this->permission;
    }

    public function update($vtsId, $formData)
    {
        $valueUpdater = $this->valueUpdater;
        $valueUpdater($vtsId, $formData);
    }

    public function getSuccessfulEditMessage()
    {
        return $this->successfulEditMessage;
    }

    public function getFormPageTitle()
    {
        return $this->formPageTitle;
    }

    public function transformFormIntoGdsTable($vtsId, array $formData)
    {
        $transformer = $this->formToGdsTableTransformer;
        return $transformer($formData);
    }

    public function getReviewPageTitle()
    {
        return $this->reviewPageTitle;
    }

    public function getReviewPageLede()
    {
        return $this->reviewPageLede;
    }

    public function getReviewPageButtonText()
    {
        return $this->reviewPageButtonText;
    }

    public function getBreadcrumbLabel()
    {
        return $this->breadCrumb;
    }
}
