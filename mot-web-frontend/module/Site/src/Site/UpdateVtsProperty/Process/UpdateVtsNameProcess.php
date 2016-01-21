<?php

namespace Site\UpdateVtsProperty\Process;

use DvsaClient\Mapper\SiteMapper;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommon\Model\VehicleTestingStation;
use Site\UpdateVtsProperty\UpdateVtsPropertyAction;
use Site\UpdateVtsProperty\UpdateVtsPropertyProcessInterface;
use Site\UpdateVtsProperty\Process\Form\NamePropertyForm;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;

class UpdateVtsNameProcess implements UpdateVtsPropertyProcessInterface, AutoWireableInterface
{
    private $propertyName = UpdateVtsPropertyAction::VTS_NAME_PROPERTY;
    private $permission = PermissionAtSite::VTS_UPDATE_NAME;
    private $requiresReview = false;
    private $submitButtonText = "Change site name";
    private $successfulEditMessage = "Site name has been successfully changed.  ";
    private $formPageTitle = "Change site name";
    private $formPartial = "site/update-vts-property/partials/edit-name";
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
        return new NamePropertyForm();
    }

    public function getSubmitButtonText()
    {
        return $this->submitButtonText;
    }

    public function getPrePopulatedData($vtsId)
    {
        $vtsData = $this->siteMapper->getById($vtsId);
        return [$this->propertyName => $vtsData->getName()];
    }

    public function getPermission()
    {
        return $this->permission;
    }

    public function update($vtsId, $formData)
    {
        $this->siteMapper->updateVtsProperty($vtsId, VehicleTestingStation::PATCH_PROPERTY_NAME, $formData[$this->propertyName]);
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
