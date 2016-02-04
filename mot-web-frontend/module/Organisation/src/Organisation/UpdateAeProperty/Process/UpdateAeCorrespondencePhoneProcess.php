<?php
namespace Organisation\UpdateAeProperty\Process;

use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\Model\AuthorisedExaminerPatchModel;
use Organisation\UpdateAeProperty\Process\Form\CorrespondencePhonePropertyForm;
use Organisation\UpdateAeProperty\UpdateAePropertyAction;

class UpdateAeCorrespondencePhoneProcess extends UpdateAeRegisteredPhoneProcess
{
    protected $propertyName = UpdateAePropertyAction::AE_CORRESPONDENCE_TELEPHONE_PROPERTY;
    protected $permission = PermissionAtOrganisation::AE_UPDATE_CORRESPONDENCE_PHONE;
    protected $requiresReview = false;
    protected $successfulEditMessage = "Correspondence telephone number has been successfully changed.";
    protected $submitButtonText = "Change correspondence telephone number";
    protected $formPageTitle = "Change correspondence telephone number";

    public function createEmptyForm()
    {
        return new CorrespondencePhonePropertyForm();
    }

    public function getPrePopulatedData($aeId)
    {
        $contact = $this->organisationMapper->getAuthorisedExaminer($aeId)->getContactByType(OrganisationContactTypeCode::CORRESPONDENCE);

        return [$this->propertyName => $contact->getPrimaryPhoneNumber()];
    }


    public function update($aeId, $formData)
    {
        $this->organisationMapper->updateAeProperty($aeId, AuthorisedExaminerPatchModel::CORRESPONDENCE_PHONE, $formData[$this->propertyName]);
    }
}
