<?php

namespace Site\UpdateVtsProperty\Process;

use Core\ViewModel\Gds\Table\GdsTable;
use DvsaClient\Mapper\SiteMapper;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommon\Model\VehicleTestingStation;
use DvsaCommon\Utility\ArrayUtils;
use Site\UpdateVtsProperty\UpdateVtsPropertyAction;
use Site\UpdateVtsProperty\UpdateVtsReviewProcessInterface;
use Site\UpdateVtsProperty\Process\Form\ClassesPropertyForm;

class UpdateVtsClassesReviewProcess implements UpdateVtsReviewProcessInterface, AutoWireableInterface
{
    private $propertyName = UpdateVtsPropertyAction::VTS_CLASSES_PROPERTY;
    private $permission = PermissionAtSite::VTS_UPDATE_CLASSES;
    private $requiresReview = true;
    private $breadcrumbLabel = "Change site classes";
    private $submitButtonText = "Review classes";
    private $successfulEditMessage = "Classes have been successfully changed.";
    private $formPageTitle = "Change classes";
    private $formPartial = "site/update-vts-property/partials/edit-classes";
    private $reviewPageTitle = "Review classes";
    private $reviewPageLede = "Please check the classes below are correct.";
    private $reviewPageButtonText = "Change classes";
    private $siteMapper;
    private $formToGdsTableTransformer;

    public function __construct(SiteMapper $siteMapper)
    {
        $this->siteMapper = $siteMapper;
        $this->formToGdsTableTransformer =
            function (array $formData) {
                $table = new GdsTable();
                $classesAsText = join(', ', $formData[$this->propertyName]);
                $table->newRow()->setLabel("Classes")->setValue($classesAsText);
                return $table;
            };
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
        return new ClassesPropertyForm();
    }

    public function getSubmitButtonText()
    {
        return $this->submitButtonText;
    }

    public function getPrePopulatedData($vtsId)
    {
        $vtsData = $this->siteMapper->getById($vtsId);
        return [$this->propertyName => $vtsData->getTestClasses()];
    }

    public function getPermission()
    {
        return $this->permission;
    }

    public function update($vtsId, $formData)
    {
        $patchData = ArrayUtils::map($formData[$this->propertyName], function ($classAsString) {
            return (int)$classAsString;
        });

        $this->siteMapper->updateVtsProperty($vtsId, VehicleTestingStation::PATCH_PROPERTY_CLASSES, $patchData);
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

        $classesData = $formData[$this->propertyName];
        $classesAsText = $classesData ? join(', ', $classesData) : 'None';

        $table->newRow()->setLabel("Vehicle Testing Station")->setValue($this->siteMapper->getById($vtsId)->getName());
        $table->newRow($this->propertyName)->setLabel("Classes")->setValue($classesAsText);
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
