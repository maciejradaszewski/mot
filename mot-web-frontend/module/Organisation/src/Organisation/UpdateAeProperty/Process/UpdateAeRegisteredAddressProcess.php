<?php

namespace Organisation\UpdateAeProperty\Process;

use Core\Formatting\AddressFormatter;
use Core\ViewModel\Gds\Table\GdsTable;
use DvsaClient\Mapper\OrganisationMapper;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\Model\AuthorisedExaminerPatchModel;
use Organisation\UpdateAeProperty\Process\Form\AddressPropertyForm;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use Organisation\UpdateAeProperty\UpdateAePropertyAction;
use Organisation\UpdateAeProperty\UpdateAeReviewProcessInterface;

class UpdateAeRegisteredAddressProcess implements UpdateAeReviewProcessInterface, AutoWireableInterface
{
    protected $propertyName = UpdateAePropertyAction::AE_REGISTERED_ADDRESS_PROPERTY;
    protected $permission = PermissionAtOrganisation::AE_UPDATE_REGISTERED_OFFICE_ADDRESS;
    protected $requiresReview = true;
    protected $breadcrumbLabel = "Change registered office address";
    protected $submitButtonText = "Review registered office address";
    protected $successfulEditMessage = "Registered office address has been successfully changed.";
    protected $formPageTitle = "Change registered office address";
    protected $formPartial = "organisation/update-ae-property/partials/edit-address";
    protected $reviewPageTitle = "Review registered address";
    protected $reviewPageLede = "Please check the address below is correct.";
    protected $reviewPageButtonText = "Change registered address";
    /**
     * @var OrganisationMapper
     */
    protected $organisationMapper;

    public function __construct(OrganisationMapper $siteMapper)
    {
        $this->organisationMapper = $siteMapper;
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
        return new AddressPropertyForm();
    }

    public function getSubmitButtonText()
    {
        return $this->submitButtonText;
    }

    public function getPrePopulatedData($aeId)
    {
        $authorisedExaminer = $this->organisationMapper->getAuthorisedExaminer($aeId);
        $contact = $authorisedExaminer->getContactByType(OrganisationContactTypeCode::REGISTERED_COMPANY);
        if (empty($contact) || empty($contact->getAddress())) {
            return [];
        }
        $address = $contact->getAddress();

        return $this->prepopulateFromAddressDto($address);
    }

    public function getPermission()
    {
        return $this->permission;
    }

    public function update($aeId, $formData)
    {
        $this->organisationMapper->updateAePropertiesWithArray($aeId,[
            AuthorisedExaminerPatchModel::REGISTERED_ADDRESS_POSTCODE => $formData[AddressPropertyForm::FIELD_POSTCODE],
            AuthorisedExaminerPatchModel::REGISTERED_ADDRESS_COUNTRY => $formData[AddressPropertyForm::FIELD_COUNTRY],
            AuthorisedExaminerPatchModel::REGISTERED_ADDRESS_LINE_1 => $formData[AddressPropertyForm::FIELD_ADDRESS_LINE_1],
            AuthorisedExaminerPatchModel::REGISTERED_ADDRESS_LINE_2 => $formData[AddressPropertyForm::FIELD_ADDRESS_LINE_2],
            AuthorisedExaminerPatchModel::REGISTERED_ADDRESS_LINE_3 => $formData[AddressPropertyForm::FIELD_ADDRESS_LINE_3],
            AuthorisedExaminerPatchModel::REGISTERED_ADDRESS_TOWN => $formData[AddressPropertyForm::FIELD_TOWN],
        ]);
    }

    public function getSuccessfulEditMessage()
    {
        return $this->successfulEditMessage;
    }

    public function getFormPageTitle()
    {
        return $this->formPageTitle;
    }

    public function transformFormIntoGdsTable($aeId, array $formData)
    {
        $table = new GdsTable();
        $authorisedExaminer = $this->organisationMapper->getAuthorisedExaminer($aeId);
        $table->newRow()->setLabel('Authorised Examiner')->setValue($authorisedExaminer->getName());
        $table->newRow("address")->setLabel("Address")
            ->setValue((new AddressFormatter())->escapeAddressToMultiLine(
                $formData[AddressPropertyForm::FIELD_ADDRESS_LINE_1],
                $formData[AddressPropertyForm::FIELD_ADDRESS_LINE_2],
                $formData[AddressPropertyForm::FIELD_ADDRESS_LINE_3],
                null,
                $formData[AddressPropertyForm::FIELD_TOWN],
                $formData[AddressPropertyForm::FIELD_COUNTRY],
                $formData[AddressPropertyForm::FIELD_POSTCODE]
            )
            ,false);

        return $table;
    }

    public function getReviewPageTitle()
    {
        return $this->reviewPageTitle;
    }

    public function getReviewPageLede()
    {
        return $this->reviewPageLede;
    }

    public function getReviewPageButtonText()
    {
        return $this->reviewPageButtonText;
    }

    public function getBreadcrumbLabel()
    {
        return $this->breadcrumbLabel;
    }

    /**
     * @param AddressDto $address
     * @return array
     */
    protected function prepopulateFromAddressDto($address)
    {
        return [
            AddressPropertyForm::FIELD_TOWN => $address->getTown(),
            AddressPropertyForm::FIELD_POSTCODE => $address->getPostcode(),
            AddressPropertyForm::FIELD_COUNTRY => $address->getCountry(),
            AddressPropertyForm::FIELD_ADDRESS_LINE_1 => $address->getAddressLine1(),
            AddressPropertyForm::FIELD_ADDRESS_LINE_2 => $address->getAddressLine2(),
            AddressPropertyForm::FIELD_ADDRESS_LINE_3 => $address->getAddressLine3(),
        ];
    }
}
