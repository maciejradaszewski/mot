<?php
namespace Organisation\UpdateAeProperty\Process;

use Core\Catalog\Authorisation\AuthForAuthorisedExaminerStatusCatalog;
use DvsaClient\Mapper\OrganisationMapper;
use DvsaClient\MapperFactory;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommon\Model\AuthorisedExaminerPatchModel;
use Organisation\UpdateAeProperty\Process\Form\StatusPropertyForm;
use Organisation\UpdateAeProperty\UpdateAePropertyAction;
use Organisation\UpdateAeProperty\UpdateAePropertyProcessInterface;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;

class UpdateAeStatusProcess implements UpdateAePropertyProcessInterface, AutoWireableInterface
{
    private $propertyName = UpdateAePropertyAction::AE_STATUS_PROPERTY;
    private $permission = PermissionAtOrganisation::AE_UPDATE_STATUS;
    private $requiresReview = false;
    private $submitButtonText = "Change status";
    private $successfulEditMessage = "Status has been successfully changed.";
    private $formPageTitle = "Change status";
    private $formPartial = "organisation/update-ae-property/partials/edit-status";
    private $organisationMapper;

    /**
     * @var AuthForAuthorisedExaminerStatusCatalog
     */
    private $authForAuthorisedExaminerStatusCatalog;
    /**
     * @var MapperFactory
     */
    private $mapper;


    public function __construct(
        OrganisationMapper $organisationMapper,
        AuthForAuthorisedExaminerStatusCatalog $authForAuthorisedExaminerStatusCatalog,
        MapperFactory $mapper
    )
    {
        $this->organisationMapper = $organisationMapper;
        $this->authForAuthorisedExaminerStatusCatalog = $authForAuthorisedExaminerStatusCatalog;
        $this->mapper = $mapper;
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
        return new StatusPropertyForm($this->authForAuthorisedExaminerStatusCatalog);
    }

    public function getSubmitButtonText()
    {
        return $this->submitButtonText;
    }

    public function getPrePopulatedData($aeId)
    {
        $aeData = $this->organisationMapper->getAuthorisedExaminer($aeId);
        return [$this->propertyName => $aeData->getAuthorisedExaminerAuthorisation()->getStatus()->getCode()];
    }

    public function getPermission()
    {
        return $this->permission;
    }

    public function update($aeId, $formData)
    {
        $this->organisationMapper->updateAeProperty($aeId, AuthorisedExaminerPatchModel::STATUS, $formData[$this->propertyName]);
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
