<?php
namespace Organisation\UpdateAeProperty\Process;

use DvsaClient\Mapper\OrganisationMapper;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommon\Model\AuthorisedExaminerPatchModel;
use Organisation\UpdateAeProperty\Process\Form\AreaOfficePropertyForm;
use Organisation\UpdateAeProperty\UpdateAePropertyAction;
use Organisation\UpdateAeProperty\UpdateAePropertyProcessInterface;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;

class UpdateAeAreaOfficeProcess implements UpdateAePropertyProcessInterface, AutoWireableInterface
{
    private $propertyName = UpdateAePropertyAction::AE_DVSA_AREA_OFFICE_STATUS_PROPERTY;
    private $permission = PermissionAtOrganisation::AE_UPDATE_DVSA_AREA_OFFICE;
    private $requiresReview = false;
    private $submitButtonText = "Change area office";
    private $successfulEditMessage = "Area office has been successfully changed.";
    private $formPageTitle = "Change area office";
    private $formPartial = "organisation/update-ae-property/partials/edit-areaoffice";
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
        return new AreaOfficePropertyForm($this->organisationMapper->getAllAreaOffices(true));
    }

    public function getSubmitButtonText()
    {
        return $this->submitButtonText;
    }

    public function getPrePopulatedData($aeId)
    {
        $aeData = $this->organisationMapper->getAuthorisedExaminer($aeId);
        return [$this->propertyName => ltrim($aeData->getAuthorisedExaminerAuthorisation()->getAssignedAreaOffice()->getSiteNumber(), '0')];
    }

    public function getPermission()
    {
        return $this->permission;
    }

    public function update($aeId, $formData)
    {
        $this->organisationMapper->updateAeProperty($aeId, AuthorisedExaminerPatchModel::AREA_OFFICE, $formData[$this->propertyName]);
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
