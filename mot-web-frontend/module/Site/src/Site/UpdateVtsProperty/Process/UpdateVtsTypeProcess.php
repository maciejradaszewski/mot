<?php

namespace Site\UpdateVtsProperty\Process;

use Core\Catalog\Vts\VtsTypeCatalog;
use DvsaClient\Mapper\SiteMapper;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommon\Model\VehicleTestingStation;
use Site\UpdateVtsProperty\Process\Form\TypePropertyForm;
use Site\UpdateVtsProperty\UpdateVtsPropertyAction;
use Site\UpdateVtsProperty\UpdateVtsPropertyProcessInterface;

class UpdateVtsTypeProcess implements UpdateVtsPropertyProcessInterface, AutoWireableInterface
{
    private $propertyName = UpdateVtsPropertyAction::VTS_TYPE_PROPERTY;
    private $permission = PermissionAtSite::VTS_UPDATE_TYPE;
    private $requiresReview = false;
    private $submitButtonText = "Change site type";
    private $successfulEditMessage = "Site type has been successfully changed.";
    private $formPageTitle = "Change site type";
    private $formPartial = "site/update-vts-property/partials/edit-type";
    private $siteMapper;

    /**
     * @var VtsTypeCatalog
     */
    private $vtsTypeCatalog;

    public function __construct(SiteMapper $siteMapper, VtsTypeCatalog $vtsTypeCatalog)
    {
        $this->siteMapper = $siteMapper;
        $this->vtsTypeCatalog = $vtsTypeCatalog;
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
        return new TypePropertyForm($this->vtsTypeCatalog);
    }

    public function getSubmitButtonText()
    {
        return $this->submitButtonText;
    }

    public function getPrePopulatedData($vtsId)
    {
        $vtsData = $this->siteMapper->getById($vtsId);
        return [$this->propertyName => $vtsData->getType()];
    }

    public function getPermission()
    {
        return $this->permission;
    }

    public function update($vtsId, $formData)
    {
        $this->siteMapper->updateVtsProperty($vtsId, VehicleTestingStation::PATCH_PROPERTY_TYPE, $formData[$this->propertyName]);
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
