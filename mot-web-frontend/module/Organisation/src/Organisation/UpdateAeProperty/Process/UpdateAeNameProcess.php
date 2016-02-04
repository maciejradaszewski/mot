<?php
namespace Organisation\UpdateAeProperty\Process;

use DvsaClient\Mapper\OrganisationMapper;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommon\Model\AuthorisedExaminerPatchModel;
use Organisation\UpdateAeProperty\UpdateAePropertyAction;
use Organisation\UpdateAeProperty\Process\Form\NamePropertyForm;
use Organisation\UpdateAeProperty\UpdateAePropertyProcessInterface;

class UpdateAeNameProcess implements UpdateAePropertyProcessInterface, AutoWireableInterface
{
    private $propertyName = UpdateAePropertyAction::AE_NAME_PROPERTY;
    private $permission = PermissionAtOrganisation::AE_UPDATE_NAME;
    private $requiresReview = false;
    private $submitButtonText = "Change business name";
    private $successfulEditMessage = "Business name has been successfully changed.";
    private $formPageTitle = "Change business name";
    private $formPartial = "organisation/update-ae-property/partials/edit-name";
    private $organisationMapper;

    public function __construct(OrganisationMapper $organisationMapper)
    {
        $this->organisationMapper = $organisationMapper;
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
        return new NamePropertyForm();
    }

    public function getSubmitButtonText()
    {
        return $this->submitButtonText;
    }

    public function getPrePopulatedData($aeId)
    {
        $aeData = $this->organisationMapper->getAuthorisedExaminer($aeId);
        return [$this->propertyName => $aeData->getName()];
    }

    public function getPermission()
    {
        return $this->permission;
    }

    public function update($aeId, $formData)
    {
        $this->organisationMapper->updateAeProperty($aeId, AuthorisedExaminerPatchModel::NAME, $formData[$this->propertyName]);
    }

    public function getSuccessfulEditMessage()
    {
        return $this->successfulEditMessage;
    }

    public function getFormPageTitle()
    {
        return $this->formPageTitle;
    }
}
