<?php
namespace Organisation\UpdateAeProperty\Process;

use DvsaClient\Mapper\OrganisationMapper;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommon\Model\AuthorisedExaminerPatchModel;
use Organisation\UpdateAeProperty\UpdateAePropertyAction;
use Organisation\UpdateAeProperty\Process\Form\TradingNamePropertyForm;
use Organisation\UpdateAeProperty\UpdateAePropertyProcessInterface;

class UpdateAeTradingNameProcess implements UpdateAePropertyProcessInterface, AutoWireableInterface
{
    private $propertyName = UpdateAePropertyAction::AE_TRADING_NAME_PROPERTY;
    private $permission = PermissionAtOrganisation::AE_UPDATE_TRADING_NAME;
    private $requiresReview = false;
    private $submitButtonText = "Change trading name";
    private $successfulEditMessage = "Trading name has been successfully changed.";
    private $formPageTitle = "Change trading name";
    private $formPartial = "organisation/update-ae-property/partials/edit-trading-name";
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
        return new TradingNamePropertyForm();
    }

    public function getSubmitButtonText()
    {
        return $this->submitButtonText;
    }

    public function getPrePopulatedData($aeId)
    {
        $aeData = $this->organisationMapper->getAuthorisedExaminer($aeId);
        return [$this->propertyName => $aeData->getTradingAs()];
    }

    public function getPermission()
    {
        return $this->permission;
    }

    public function update($aeId, $formData)
    {
        $this->organisationMapper->updateAeProperty($aeId, AuthorisedExaminerPatchModel::TRADING_NAME, $formData[$this->propertyName]);
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
