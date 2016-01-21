<?php

namespace Site\UpdateVtsProperty;

use Core\Action\ActionResult;
use Core\Action\RedirectToRoute;
use Core\Routing\VtsRouteList;
use DvsaClient\Mapper\SiteMapper;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Exception\NotImplementedException;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommon\HttpRestJson\Exception\ValidationException;
use Zend\Form\Form;
use Zend\View\Helper\Url;

class UpdateVtsPropertyAction implements AutoWireableInterface
{
    const VTS_NAME_PROPERTY = 'name';
    const VTS_CLASSES_PROPERTY = 'classes';
    const VTS_STATUS_PROPERTY = 'status';
    const VTS_TYPE_PROPERTY = 'type';
    const VTS_EMAIL_PROPERTY = 'email';
    const VTS_ADDRESS_PROPERTY = 'address';
    const VTS_PHONE_PROPERTY = 'phone';
    const VTS_COUNTRY_PROPERTY = 'country';

    private $formSession;

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
        $this->formSession = $session;
        $this->siteMapper = $siteMapper;
        $this->authorisationService = $authorisationService;
        $this->processBuilder = $processBuilder;
        $this->urlHelper = $urlHelper;
    }

    public function execute($isPost, $propertyName, $vtsId, $formUuid, array $formData = [])
    {
        if ($isPost) {
            return $this->executePost($propertyName, $vtsId, $formData);
        } else {
            return $this->executeGet($propertyName, $vtsId, $formUuid);
        }
    }

    private function executeGet($propertyName, $vtsId, $formUuid)
    {
        $process = $this->processBuilder->get($propertyName);

        $permission = $process->getPermission();
        $this->authorisationService->assertGrantedAtSite($permission, $vtsId);

        if ($formUuid) {
            $formData = $this->formSession->get($vtsId, $propertyName, $formUuid);
            if ($formData === null) {
                return $this->redirectBackWithoutTheFormUuidInQuery($vtsId, $propertyName);
            }
        } else {
            $formData = $process->getPrePopulatedData($vtsId);
        }

        $form = $process->createEmptyForm();
        $form->setData($formData);

        return $this->buildActionResult($vtsId, $process, $form);
    }

    private function executePost($propertyName, $vtsId, array $formData = [])
    {
        $process = $this->processBuilder->get($propertyName);

        $permission = $process->getPermission();
        $this->authorisationService->assertGrantedAtSite($permission, $vtsId);

        $form = $process->createEmptyForm();
        $form->setData($formData);

        $errors = [];
        if ($form->isValid()) {
            if ($process->getRequiresReview()) {
                return $this->storeFormInSessionAndRedirectToReviewChangesPage($vtsId, $propertyName, $formData);
            }

            try {
                return $this->updateAndRedirectToVtsPage($process, $vtsId, $formData);
            } catch (ValidationException $exception) {
                $errors = $exception->getDisplayMessages();
            }
        }

        return $this->buildActionResult($vtsId, $process, $form, $errors);
    }

    private function updateAndRedirectToVtsPage(UpdateVtsPropertyProcessInterface $process, $vtsId, array $formData)
    {
        $process->update($vtsId, $formData);
        $result = new RedirectToRoute(VtsRouteList::VTS, ['id' => $vtsId]);
        $result->addSuccessMessage($process->getSuccessfulEditMessage());

        return $result;
    }

    private function storeFormInSessionAndRedirectToReviewChangesPage($vtsId, $propertyName, $formData)
    {
        $formUuid = $this->formSession->store($vtsId, $propertyName, $formData);

        return new RedirectToRoute(VtsRouteList::VTS_EDIT_PROPERTY_REVIEW,
            ['id' => $vtsId, 'propertyName' => $propertyName, 'formUuid' => $formUuid]
        );
    }

    private function redirectBackWithoutTheFormUuidInQuery($vtsId, $propertyName)
    {
        return new RedirectToRoute(VtsRouteList::VTS_EDIT_PROPERTY, ['id' => $vtsId, 'propertyName' => $propertyName]);
    }

    private function buildActionResult($vtsId, UpdateVtsPropertyProcessInterface $process, Form $form, $errors = [])
    {
        $updateVtsPropertyBreadcrumbs = new UpdateVtsPropertyBreadcrumbs(
            $this->siteMapper->getById($vtsId),
            $this->authorisationService,
            $this->urlHelper,
            $process->getFormPageTitle()
        );
        $breadcrumbs = $updateVtsPropertyBreadcrumbs->create();

        $vm = new UpdateVtsPropertyViewModel(
            $vtsId, $process->getPropertyName(), $process->getFormPartial(), $process->getSubmitButtonText(), $form
        );

        $actionResult = new ActionResult();
        $actionResult->setViewModel($vm);
        $actionResult->addErrorMessages($errors);

        $actionResult->layout()->setPageTitle($process->getFormPageTitle());
        $actionResult->layout()->setPageSubTitle("Vehicle Testing Station");

        $actionResult->layout()->setTemplate('layout/layout-govuk.phtml');
        $actionResult->layout()->setBreadcrumbs($breadcrumbs);

        return $actionResult;
    }
}
