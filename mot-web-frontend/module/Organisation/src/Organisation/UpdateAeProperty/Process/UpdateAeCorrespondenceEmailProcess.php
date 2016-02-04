<?php

namespace Organisation\UpdateAeProperty\Process;

use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\Model\AuthorisedExaminerPatchModel;
use Organisation\UpdateAeProperty\Process\Form\CorrespondenceEmailPropertyForm;
use Organisation\UpdateAeProperty\UpdateAePropertyAction;

class UpdateAeCorrespondenceEmailProcess extends UpdateAeRegisteredEmailProcess
{
    protected $propertyName = UpdateAePropertyAction::AE_CORRESPONDENCE_EMAIL_PROPERTY;
    protected $permission = PermissionAtOrganisation::AE_UPDATE_CORRESPONDENCE_EMAIL;
    protected $successfulEditMessage = "Correspondence email address has been successfully changed.";
    protected $submitButtonText = "Change correspondence email address";
    protected $formPageTitle = "Change correspondence email address";

    public function createEmptyForm()
    {
        return new CorrespondenceEmailPropertyForm();
    }

    public function getPrePopulatedData($aeId)
    {
        $contact = $this->organisationMapper->getAuthorisedExaminer($aeId)->getContactByType(OrganisationContactTypeCode::CORRESPONDENCE);

        return [$this->propertyName => $contact->getPrimaryEmailAddress()];
    }


    public function update($aeId, $formData)
    {
        $this->organisationMapper->updateAeProperty($aeId, AuthorisedExaminerPatchModel::CORRESPONDENCE_EMAIL, $formData[$this->propertyName]);
    }
}
