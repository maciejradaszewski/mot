<?php

namespace Site\UpdateVtsProperty;

use Core\Action\ActionResult;
use Core\Action\RedirectToRoute;
use Core\Action\RedirectToUrl;
use Core\Routing\VtsRouteList;
use Core\Routing\VtsRoutes;
use Core\ViewModel\Gds\Table\GdsTable;
use DvsaClient\Mapper\SiteMapper;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Exception\NotImplementedException;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommon\HttpRestJson\Exception\ValidationException;
use Zend\View\Helper\Url;

class UpdateVtsPropertyReviewAction implements AutoWireableInterface
{
    const VTS_NAME_PROPERTY = 'name';
    const VTS_CLASSES_PROPERTY = 'classes';

    private $formSessionContainer;

    private $siteMapper;

    private $authorisationService;
    /**
     * @var UpdateVtsPropertyProcessBuilder
     */
    private $processBuilder;

    private $urlHelper;

    public function __construct(
        UpdateVtsPropertyFormSession $session,
        SiteMapper $siteMapper,
        MotAuthorisationServiceInterface $authorisationService,
        UpdateVtsPropertyProcessBuilder $processBuilder,
        Url $urlHelper
    )
    {
        $this->formSessionContainer = $session;
        $this->siteMapper = $siteMapper;
        $this->authorisationService = $authorisationService;
        $this->processBuilder = $processBuilder;
        $this->urlHelper = $urlHelper;
    }

    public function execute($isPost, $propertyName, $vtsId, $formUuid)
    {
        /** @var UpdateVtsReviewProcessInterface $process */
        $process = $this->processBuilder->get($propertyName);

        $permission = $process->getPermission();
        $this->authorisationService->assertGrantedAtSite($permission, $vtsId);

        $formData = $this->formSessionContainer->get($vtsId, $propertyName, $formUuid);

        if (!$isPost) {
            if ($formData === null) {
                $result = new RedirectToRoute(VtsRouteList::VTS_EDIT_PROPERTY, ['id' => $vtsId, 'propertyName' => $propertyName]);
                return $result;
            }
        }

        $gdsTable = $process->transformFormIntoGdsTable($vtsId, $formData);

        $errors = [];
        if ($isPost) {
            try {
                $process->update($vtsId, $formData);
                $result = new RedirectToUrl(VtsRoutes::of($this->urlHelper)->vts($vtsId));
                $result->addSuccessMessage($process->getSuccessfulEditMessage());

                return $result;
            } catch (ValidationException $exception) {
                $errors = $exception->getDisplayMessages();
            }
        }

        return $this->buildActionResult($vtsId, $process, $formUuid, $formData, $gdsTable, $errors);
    }

    private function buildActionResult($vtsId, UpdateVtsReviewProcessInterface $process, $formUuid, $formData, GdsTable $table, $errors = null)
    {
        $updateVtsPropertyBreadcrumbs = new UpdateVtsPropertyBreadcrumbs(
            $this->siteMapper->getById($vtsId),
            $this->authorisationService,
            $this->urlHelper,
            $process->getBreadcrumbLabel()
        );
        $breadcrumbs = $updateVtsPropertyBreadcrumbs->create();

        $vm = new UpdateVtsPropertyReviewViewModel($vtsId, $process->getPropertyName(), $formUuid, $process->getReviewPageButtonText(), $formData, $table);

        $actionResult = new ActionResult();
        $actionResult->setViewModel($vm);
        $actionResult->addErrorMessages($errors);

        $actionResult->layout()->setTemplate('layout/layout-govuk.phtml');

        $actionResult->layout()->setPageTitle($process->getReviewPageTitle());
        $actionResult->layout()->setPageSubTitle("Vehicle Testing Station");
        $actionResult->layout()->setPageLede($process->getReviewPageLede());
        $actionResult->layout()->setBreadcrumbs($breadcrumbs);

        return $actionResult;
    }
}
