<?php

namespace Organisation\UpdateAeProperty\Process;

use Core\Catalog\Organisation\OrganisationCompanyTypeCatalog;
use DvsaClient\Mapper\OrganisationMapper;
use DvsaClient\MapperFactory;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Enum\CompanyTypeCode;
use DvsaCommon\Exception\NotImplementedException;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommon\Model\AuthorisedExaminerPatchModel;
use Organisation\UpdateAeProperty\Process\Form\BusinessTypePropertyForm;
use Organisation\UpdateAeProperty\UpdateAePropertyAction;
use Organisation\UpdateAeProperty\UpdateAePropertyProcessInterface;

class UpdateAeBusinessTypeProcess implements UpdateAePropertyProcessInterface, AutoWireableInterface
{
    private $propertyCompanyNumber = UpdateAePropertyAction::AE_COMPANY_NUMBER_PROPERTY;
    private $propertyName = UpdateAePropertyAction::AE_BUSINESS_TYPE_PROPERTY;
    private $permission = PermissionAtOrganisation::AE_UPDATE_TYPE;
    private $requiresReview = false;
    private $submitButtonText = "Change business type";
    private $successfulEditMessage = "Business type has been successfully changed.";
    private $formPageTitle = "Change business type";
    private $formPartial = "organisation/update-ae-property/partials/edit-business-type";

    /**
     * @var OrganisationMapper
     */
    private $organisationMapper;

    /**
     * @var OrganisationCompanyTypeCatalog
     */
    private $organisationCompanyTypeCatalog;
    /**
     * @var MapperFactory
     */
    private $mapper;

    public function __construct(
        OrganisationMapper $organisationMapper,
        OrganisationCompanyTypeCatalog $organisationCompanyTypeCatalog,
        MapperFactory $mapper
    )
    {
        $this->organisationMapper = $organisationMapper;
        $this->organisationCompanyTypeCatalog = $organisationCompanyTypeCatalog;
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
        return new BusinessTypePropertyForm($this->organisationCompanyTypeCatalog);
    }

    public function getSubmitButtonText()
    {
        return $this->submitButtonText;
    }

    public function getPrePopulatedData($aeId)
    {
        $authorisedExaminer = $this->organisationMapper->getAuthorisedExaminer($aeId);
        $companyType = $this->organisationCompanyTypeCatalog->getByName($authorisedExaminer->getCompanyType());
        $return = [$this->propertyCompanyNumber => $authorisedExaminer->getRegisteredCompanyNumber()];
        if(!is_null($companyType)){
            $return[$this->propertyName] = $companyType->getCode();
        }

        return $return;
    }

    public function getPermission()
    {
        return $this->permission;
    }

    public function update($aeId, $formData)
    {
        $companyNumber = $formData[$this->propertyCompanyNumber];
        if($formData[$this->propertyName] != CompanyTypeCode::COMPANY){
            return $this->organisationMapper->updateAeProperty($aeId, AuthorisedExaminerPatchModel::TYPE,
                $formData[$this->propertyName]);
        } else {
            return $this->organisationMapper->updateAePropertiesWithArray($aeId, [
                AuthorisedExaminerPatchModel::TYPE =>  $formData[$this->propertyName],
                AuthorisedExaminerPatchModel::COMPANY_NUMBER =>  $companyNumber,
            ]);
        }
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
