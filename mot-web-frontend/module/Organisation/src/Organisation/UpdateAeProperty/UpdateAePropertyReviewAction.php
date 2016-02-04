<?php

namespace Organisation\UpdateAeProperty;

use Core\Action\ActionResult;
use Core\Action\RedirectToRoute;
use Core\Action\RedirectToUrl;
use Core\Routing\AeRouteList;
use Core\Routing\AeRoutes;
use Core\ViewModel\Gds\Table\GdsTable;
use DvsaClient\Mapper\OrganisationMapper;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommon\HttpRestJson\Exception\ValidationException;
use Zend\View\Helper\Url;

class UpdateAePropertyReviewAction implements AutoWireableInterface
{
    const AE_NAME_PROPERTY = 'name';
    const AE_CLASSES_PROPERTY = 'classes';

    private $formSessionContainer;

    /**
     * @var OrganisationMapper
     */
    private $organisationMapper;

    private $authorisationService;
    /**
     * @var UpdateAePropertyProcessBuilder
     */
    private $processBuilder;

    private $urlHelper;

    public function __construct(
        UpdateAePropertyFormSession $session,
        OrganisationMapper $organisationMapper,
        MotAuthorisationServiceInterface $authorisationService,
        UpdateAePropertyProcessBuilder $processBuilder,
        Url $urlHelper
    )
    {
        $this->formSessionContainer = $session;
        $this->organisationMapper = $organisationMapper;
        $this->authorisationService = $authorisationService;
        $this->processBuilder = $processBuilder;
        $this->urlHelper = $urlHelper;
    }

    public function execute($isPost, $propertyName, $aeId, $formUuid)
    {
        /** @var UpdateAeReviewProcessInterface $process */
        $process = $this->processBuilder->get($propertyName);

        $permission = $process->getPermission();
        $this->authorisationService->assertGrantedAtOrganisation($permission, $aeId);

        $formData = $this->formSessionContainer->get($aeId, $propertyName, $formUuid);

        if (!$isPost) {
            if ($formData === null) {
                $result = new RedirectToRoute(AeRouteList::AE_EDIT_PROPERTY, ['id' => $aeId, 'propertyName' => $propertyName]);
                return $result;
            }
        }

        $gdsTable = $process->transformFormIntoGdsTable($aeId, $formData);

        $errors = [];
        if ($isPost) {
            try {
                $process->update($aeId, $formData);
                $result = new RedirectToUrl(AeRoutes::of($this->urlHelper)->ae($aeId));
                $result->addSuccessMessage($process->getSuccessfulEditMessage());

                return $result;
            } catch (ValidationException $exception) {
                $errors = $exception->getDisplayMessages();
            }
        }

        return $this->buildActionResult($aeId, $process, $formUuid, $formData, $gdsTable, $errors);
    }

    private function buildActionResult($aeId, UpdateAeReviewProcessInterface $process, $formUuid, $formData, GdsTable $table, $errors = null)
    {
        $updateAePropertyBreadcrumbs = new UpdateAePropertyBreadcrumbs(
            $this->organisationMapper->getAuthorisedExaminer($aeId),
            $this->authorisationService,
            $this->urlHelper,
            $process->getBreadcrumbLabel()
        );
        $breadcrumbs = $updateAePropertyBreadcrumbs->create();

        $vm = new UpdateAePropertyReviewViewModel($aeId, $process->getPropertyName(), $formUuid, $process->getReviewPageButtonText(), $formData, $table);

        $actionResult = new ActionResult();
        $actionResult->setViewModel($vm);
        $actionResult->addErrorMessages($errors);

        $actionResult->layout()->setTemplate('layout/layout-govuk.phtml');

        $actionResult->layout()->setPageTitle($process->getReviewPageTitle());
        $actionResult->layout()->setPageSubTitle("Authorised Examiner");
        $actionResult->layout()->setPageLede($process->getReviewPageLede());
        $actionResult->layout()->setBreadcrumbs($breadcrumbs);

        return $actionResult;
    }
}
