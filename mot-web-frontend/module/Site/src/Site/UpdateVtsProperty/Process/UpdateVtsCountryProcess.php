<?php

namespace Site\UpdateVtsProperty\Process;

use Core\Catalog\CountryCatalog;
use Core\ViewModel\Gds\Table\GdsTable;
use DvsaClient\Mapper\SiteMapper;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Enum\CountryCode;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use Site\UpdateVtsProperty\Process\Form\CountryPropertyForm;
use DvsaCommon\Model\VehicleTestingStation;
use Site\UpdateVtsProperty\UpdateVtsPropertyAction;
use Site\UpdateVtsProperty\UpdateVtsReviewProcessInterface;
use Zend\Form\Element\Radio;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class UpdateVtsCountryProcess implements UpdateVtsReviewProcessInterface, AutoWireableInterface
{
    private $breadcrumbLabel = "Change site country";
    private $propertyName = UpdateVtsPropertyAction::VTS_COUNTRY_PROPERTY;
    private $permission = PermissionAtSite::VTS_UPDATE_COUNTRY;
    private $requiresReview = false;
    private $submitButtonText = "Change country";
    private $successfulEditMessage = "Country has been successfully changed.";
    private $formPageTitle = "Change country";
    private $formPartial = "site/update-vts-property/partials/edit-country";
    private $reviewPageTitle = "";
    private $reviewPageLede = "";
    private $reviewPageButtonText = "";
    private $siteMapper;

    /**
     * @var CountryCatalog
     */
    private $countryCatalog;

    public function __construct(SiteMapper $siteMapper, CountryCatalog $vtsCountryCatalog)
    {
        $this->siteMapper = $siteMapper;
        $this->countryCatalog = $vtsCountryCatalog;
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
        return new CountryPropertyForm($this->countryCatalog);
    }

    public function getSubmitButtonText()
    {
        return $this->submitButtonText;
    }

    public function getPrePopulatedData($vtsId)
    {
        $vtsData = $this->siteMapper->getById($vtsId);
        $country = CountryCode::ENGLAND;

        if ($vtsData->isDualLanguage()) {
            $country = CountryCode::WALES;
        } else if ($vtsData->isScottishBankHoliday()) {
            $country = CountryCode::SCOTLAND;
        }

        return [$this->propertyName => $country];
    }

    public function getPermission()
    {
        return $this->permission;
    }

    public function update($vtsId, $formData)
    {
        $this->siteMapper->updateVtsProperty(
            $vtsId,
            VehicleTestingStation::PATCH_PROPERTY_COUNTRY,
            $formData[$this->propertyName]
        );
    }

    public function getSuccessfulEditMessage()
    {
        return $this->successfulEditMessage;
    }

    public function getFormPageTitle()
    {
        return $this->formPageTitle;
    }

    public function transformFormIntoGdsTable($vtsId, array $formData)
    {
        $table = new GdsTable();
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
}
