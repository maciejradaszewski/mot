<?php
namespace Organisation\UpdateAeProperty\Process;

use DvsaClient\MapperFactory;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Date\DateTimeDisplayFormat;
use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\AddressLine1Input;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\AddressLine2Input;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\AddressLine3Input;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\CountryInput;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\DateOfBirthInput;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\FamilyNameInput;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\FirstNameInput;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\MiddleNameInput;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\PostcodeInput;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\TownInput;
use DvsaCommon\Model\AuthorisedExaminerPatchModel;
use Organisation\UpdateAeProperty\Process\Form\AepForm;
use Organisation\UpdateAeProperty\Process\Form\AreaOfficePropertyForm;
use Organisation\UpdateAeProperty\UpdateAePropertyAction;
use Organisation\UpdateAeProperty\UpdateAeReviewProcessInterface;
use Core\ViewModel\Gds\Table\GdsTable;
use Core\Formatting\AddressFormatter;

class CreateAePrincipalProcess implements UpdateAeReviewProcessInterface, AutoWireableInterface
{
    private $propertyName = UpdateAePropertyAction::AE_CREATE_AEP_PROPERTY;
    private $permission = PermissionAtOrganisation::AUTHORISED_EXAMINER_PRINCIPAL_CREATE;
    private $requiresReview = true;
    protected $breadcrumbLabel = "Add principal";
    private $submitButtonText = "Review principal";
    private $successfulEditMessage = "%s has been added as a Authorised Examiner Principal.";
    private $formPageTitle = "Add a principal";
    private $formPartial = "organisation/update-ae-property/partials/add-principle";
    private $personMapper;
    protected $reviewPageTitle = "Review principal";
    protected $reviewPageLede = "Please check the information below is correct.";
    protected $reviewPageButtonText = "Add principal";
    private $aepName = "";

    public function __construct(MapperFactory $mapperFactory)
    {
        $this->personMapper = $mapperFactory->Person;
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
        return new AepForm();
    }

    public function getSubmitButtonText()
    {
        return $this->submitButtonText;
    }

    public function getPrePopulatedData($aeId)
    {
        return [];
    }

    public function getPermission()
    {
        return $this->permission;
    }

    public function update($aeId, $formData)
    {
        $dob = new \DateTime();
        $dob->setDate((int) $formData[AepForm::FIELD_DOB_YEAR], (int) $formData[AepForm::FIELD_DOB_MONTH], (int) $formData[AepForm::FIELD_DOB_DAY]);

        $formData[DateOfBirthInput::FIELD] = $dob->format(DateTimeApiFormat::FORMAT_ISO_8601_DATE_ONLY);

        unset($formData[AepForm::FIELD_DOB_DAY]);
        unset($formData[AepForm::FIELD_DOB_MONTH]);
        unset($formData[AepForm::FIELD_DOB_YEAR]);

        $this->personMapper->createPrincipalsForOrganisation($aeId, $formData);
    }

    public function getSuccessfulEditMessage()
    {
        return sprintf($this->successfulEditMessage, $this->getAepName());
    }

    public function getFormPageTitle()
    {
        return $this->formPageTitle;
    }

    public function transformFormIntoGdsTable($aeId, array $formData)
    {
        $table = new GdsTable();

        $name = $this->getAepName($formData);

        $dob = new \DateTime();
        $dob->setDate((int) $formData[AepForm::FIELD_DOB_YEAR], (int) $formData[AepForm::FIELD_DOB_MONTH], (int) $formData[AepForm::FIELD_DOB_DAY]);

        $table->newRow("name")->setLabel("Name")->setValue($name);
        $table->newRow("dob")->setLabel("Date of birth")->setValue($dob->format(DateTimeDisplayFormat::FORMAT_DATE));
        $table->newRow("address")->setLabel("Address")
            ->setValue((new AddressFormatter())->escapeAddressToMultiLine(
                $formData[AddressLine1Input::FIELD],
                $formData[AddressLine2Input::FIELD],
                $formData[AddressLine3Input::FIELD],
                null,
                $formData[TownInput::FIELD],
                $formData[CountryInput::FIELD],
                $formData[PostcodeInput::FIELD]
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
     * @param array $formData
     * @return string
     */
    private function getAepName(array $formData = [])
    {
        if ($formData) {
            $nameData = [$formData[FirstNameInput::FIELD], $formData[MiddleNameInput::FIELD], $formData[FamilyNameInput::FIELD]];
            $nameData = array_filter($nameData);
            $this->aepName = implode(" ", $nameData);
        }

        return $this->aepName;
    }
}
