<?php

namespace Site\UpdateVtsProperty\Process;

use DvsaClient\Mapper\SiteMapper;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Enum\SiteContactTypeCode;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommon\Model\VehicleTestingStation;
use Site\UpdateVtsProperty\Process\Form\EmailPropertyForm;
use Site\UpdateVtsProperty\UpdateVtsPropertyAction;
use Site\UpdateVtsProperty\UpdateVtsPropertyProcessInterface;

class UpdateVtsEmailProcess implements UpdateVtsPropertyProcessInterface, AutoWireableInterface
{
    private $propertyName = UpdateVtsPropertyAction::VTS_EMAIL_PROPERTY;
    private $permission = PermissionAtSite::VTS_UPDATE_EMAIL;
    private $requiresReview = false;
    private $submitButtonText = "Change email address";
    private $successfulEditMessage = "Email address has been successfully changed.";
    private $formPageTitle = "Change email address";
    private $formPartial = "site/update-vts-property/partials/edit-email";
    private $siteMapper;

    public function __construct(SiteMapper $siteMapper)
    {
        $this->siteMapper = $siteMapper;
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
        return new EmailPropertyForm();
    }

    public function getSubmitButtonText()
    {
        return $this->submitButtonText;
    }

    public function getPrePopulatedData($vtsId)
    {
        $contact = $this->siteMapper->getById($vtsId)->getContactByType(SiteContactTypeCode::BUSINESS);

        return [$this->propertyName => $contact->getPrimaryEmailAddress()];
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

    public function update($vtsId, $formData)
    {
        $this->siteMapper->updateVtsContactProperty($vtsId, VehicleTestingStation::PATCH_PROPERTY_EMAIL, $formData[$this->propertyName]);
    }

    public function getRequiresReview()
    {
        return $this->requiresReview;
    }
}
