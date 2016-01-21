<?php

namespace Site\UpdateVtsProperty\Process;

use DvsaClient\Mapper\SiteMapper;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommon\Model\VehicleTestingStation;
use Site\UpdateVtsProperty\UpdateVtsPropertyAction;
use Site\UpdateVtsProperty\UpdateVtsPropertyProcessInterface;
use Site\UpdateVtsProperty\Process\Form\StatusPropertyForm;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;

class UpdateVtsStatusProcess implements UpdateVtsPropertyProcessInterface, AutoWireableInterface
{
    private $propertyName = UpdateVtsPropertyAction::VTS_STATUS_PROPERTY;
    private $permission = PermissionAtSite::VTS_UPDATE_STATUS;
    private $requiresReview = false;
    private $submitButtonText = "Change site status";
    private $successfulEditMessage = "Site status has been successfully changed.";
    private $formPageTitle = "Change status";
    private $formPartial = "site/update-vts-property/partials/edit-status";
    private $siteMapper;

    public function __construct(SiteMapper $siteMapper)
    {
        $this->siteMapper = $siteMapper;
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
        return new StatusPropertyForm();
    }

    public function getSubmitButtonText()
    {
        return $this->submitButtonText;
    }

    public function getPrePopulatedData($vtsId)
    {
        $vtsData = $this->siteMapper->getById($vtsId);
        return [$this->propertyName => $vtsData->getStatus()];
    }

    public function getPermission()
    {
        return $this->permission;
    }

    public function update($vtsId, $formData)
    {
        $this->siteMapper->updateVtsProperty($vtsId, VehicleTestingStation::PATCH_PROPERTY_STATUS, $formData[$this->propertyName]);
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
