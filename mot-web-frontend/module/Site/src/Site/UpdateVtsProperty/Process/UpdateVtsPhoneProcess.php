<?php

namespace Site\UpdateVtsProperty\Process;

use DvsaClient\Mapper\SiteMapper;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Enum\SiteContactTypeCode;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommon\Model\VehicleTestingStation;
use Site\UpdateVtsProperty\UpdateVtsPropertyAction;
use Site\UpdateVtsProperty\UpdateVtsPropertyProcessInterface;
use Site\UpdateVtsProperty\Process\Form\PhonePropertyForm;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;

class UpdateVtsPhoneProcess implements UpdateVtsPropertyProcessInterface, AutoWireableInterface
{
    private $propertyName = UpdateVtsPropertyAction::VTS_PHONE_PROPERTY;
    private $permission = PermissionAtSite::VTS_UPDATE_PHONE;
    private $requiresReview = false;
    private $submitButtonText = "Change telephone number";
    private $successfulEditMessage = "Telephone has been successfully changed.";
    private $formPageTitle = "Change telephone number";
    private $formPartial = "site/update-vts-property/partials/edit-phone";
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
        return new PhonePropertyForm();
    }

    public function getSubmitButtonText()
    {
        return $this->submitButtonText;
    }

    public function getPrePopulatedData($vtsId)
    {
        $vtsData = $this->siteMapper->getById($vtsId);

        $contactDto = $vtsData->getContactByType(SiteContactTypeCode::BUSINESS);

        $phoneNumber = $contactDto ? $contactDto->getPrimaryPhoneNumber() : '';

        return [$this->propertyName => $phoneNumber];
    }

    public function getPermission()
    {
        return $this->permission;
    }

    public function update($vtsId, $formData)
    {
        $this->siteMapper->updateVtsContactProperty($vtsId, VehicleTestingStation::PATCH_PROPERTY_PHONE, $formData[$this->propertyName]);
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
