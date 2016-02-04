<?php

namespace Organisation\UpdateAeProperty\Process;

use DvsaClient\Mapper\OrganisationMapper;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommon\Model\AuthorisedExaminerPatchModel;
use Organisation\UpdateAeProperty\UpdateAePropertyAction;
use Organisation\UpdateAeProperty\Process\Form\RegisteredPhonePropertyForm;
use Organisation\UpdateAeProperty\UpdateAePropertyProcessInterface;

class UpdateAeRegisteredPhoneProcess implements UpdateAePropertyProcessInterface, AutoWireableInterface
{
    protected $propertyName = UpdateAePropertyAction::AE_REGISTERED_TELEPHONE_PROPERTY;
    protected $permission = PermissionAtOrganisation::AE_UPDATE_REGISTERED_OFFICE_PHONE;
    protected $requiresReview = false;
    protected $submitButtonText = "Change registered office telephone number";
    protected $successfulEditMessage = "Registered office telephone number has been successfully changed.";
    protected $formPageTitle = "Change registered office telephone number";
    protected $formPartial = "organisation/update-ae-property/partials/edit-phone";
    protected $organisationMapper;

    public function __construct(OrganisationMapper $siteMapper)
    {
        $this->organisationMapper = $siteMapper;
    }

    public function getPropertyName()
    {
        return $this->propertyName;
    }

    public function getFormPartial()
    {
        return $this->formPartial;
    }

    public function createEmptyForm()
    {
        return new RegisteredPhonePropertyForm();
    }

    public function getSubmitButtonText()
    {
        return $this->submitButtonText;
    }

    public function getPrePopulatedData($aeId)
    {
        $contact = $this->organisationMapper->getAuthorisedExaminer($aeId)->getContactByType(OrganisationContactTypeCode::REGISTERED_COMPANY);

        return [$this->propertyName => $contact->getPrimaryPhoneNumber()];
    }

    public function getPermission()
    {
        return $this->permission;
    }

    public function getSuccessfulEditMessage()
    {
        return $this->successfulEditMessage;
    }

    public function getFormPageTitle()
    {
        return $this->formPageTitle;
    }

    public function update($aeId, $formData)
    {
        $this->organisationMapper->updateAeProperty($aeId, AuthorisedExaminerPatchModel::REGISTERED_PHONE, $formData[$this->propertyName]);
    }

    public function getRequiresReview()
    {
        return $this->requiresReview;
    }
}
