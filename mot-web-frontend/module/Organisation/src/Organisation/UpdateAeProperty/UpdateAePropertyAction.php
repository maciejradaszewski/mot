<?php

namespace Organisation\UpdateAeProperty;

use Core\Action\ActionResult;
use Core\Action\RedirectToRoute;
use Core\Routing\AeRouteList;
use DvsaClient\Mapper\OrganisationMapper;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommon\HttpRestJson\Exception\ValidationException;
use Zend\Form\Form;
use Zend\View\Helper\Url;

class UpdateAePropertyAction implements AutoWireableInterface
{
    const AE_NAME_PROPERTY = 'name';
    const AE_TRADING_NAME_PROPERTY = 'trading-name';
    const AE_BUSINESS_TYPE_PROPERTY = 'business-type';
    const AE_STATUS_PROPERTY = 'status';
    const AE_DVSA_AREA_OFFICE_STATUS_PROPERTY = 'areaoffice';
    const AE_REGISTERED_ADDRESS_PROPERTY = 'registered-address';
    const AE_REGISTERED_EMAIL_PROPERTY = 'registered-email';
    const AE_REGISTERED_TELEPHONE_PROPERTY = 'registered-telephone';
    const AE_CORRESPONDENCE_ADDRESS_PROPERTY = 'correspondence-address';
    const AE_CORRESPONDENCE_EMAIL_PROPERTY = 'correspondence-email';
    const AE_CORRESPONDENCE_TELEPHONE_PROPERTY = 'correspondence-telephone';
    const AE_COMPANY_NUMBER_PROPERTY = 'company-number';

    private $formSession;

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
        $this->formSession = $session;
        $this->organisationMapper = $organisationMapper;
        $this->authorisationService = $authorisationService;
        $this->processBuilder = $processBuilder;
        $this->urlHelper = $urlHelper;
    }

    public function execute($isPost, $propertyName, $aeId, $formUuid, array $formData = [])
    {
        if ($isPost) {
            return $this->executePost($propertyName, $aeId, $formData);
        } else {
            return $this->executeGet($propertyName, $aeId, $formUuid);
        }
    }

    private function executeGet($propertyName, $aeId, $formUuid)
    {
        $process = $this->processBuilder->get($propertyName);

        $permission = $process->getPermission();
        $this->authorisationService->assertGrantedAtOrganisation($permission, $aeId);

        if ($formUuid) {
            $formData = $this->formSession->get($aeId, $propertyName, $formUuid);
            if ($formData === null) {
                return $this->redirectBackWithoutTheFormUuidInQuery($aeId, $propertyName);
            }
        } else {
            $formData = $process->getPrePopulatedData($aeId);
        }

        $form = $process->createEmptyForm();
        $form->setData($formData);

        return $this->buildActionResult($aeId, $process, $form);
    }

    private function executePost($propertyName, $aeId, array $formData = [])
    {
        $process = $this->processBuilder->get($propertyName);

        $permission = $process->getPermission();
        $this->authorisationService->assertGrantedAtOrganisation($permission, $aeId);

        $form = $process->createEmptyForm();
        $form->setData($formData);

        $errors = [];
        if ($form->isValid()) {
            if ($process->getRequiresReview()) {
                return $this->storeFormInSessionAndRedirectToReviewChangesPage($aeId, $propertyName, $formData);
            }

            try {
                return $this->updateAndRedirectToAePage($process, $aeId, $formData);
            } catch (ValidationException $exception) {
                $errors = $exception->getDisplayMessages();
            }
        }

        return $this->buildActionResult($aeId, $process, $form, $errors);
    }

    private function updateAndRedirectToAePage(UpdateAePropertyProcessInterface $process, $aeId, array $formData)
    {
        $process->update($aeId, $formData);
        $result = new RedirectToRoute(AeRouteList::AE, ['id' => $aeId]);
        $result->addSuccessMessage($process->getSuccessfulEditMessage());

        return $result;
    }

    private function storeFormInSessionAndRedirectToReviewChangesPage($aeId, $propertyName, $formData)
    {
        $formUuid = $this->formSession->store($aeId, $propertyName, $formData);

        return new RedirectToRoute(AeRouteList::AE_EDIT_PROPERTY_REVIEW,
            ['id' => $aeId, 'propertyName' => $propertyName, 'formUuid' => $formUuid]
        );
    }

    private function redirectBackWithoutTheFormUuidInQuery($aeId, $propertyName)
    {
        return new RedirectToRoute(AeRouteList::AE_EDIT_PROPERTY, ['id' => $aeId, 'propertyName' => $propertyName]);
    }

    private function buildActionResult($aeId, UpdateAePropertyProcessInterface $process, Form $form, $errors = [])
    {
        $updateAePropertyBreadcrumbs = new UpdateAePropertyBreadcrumbs(
            $this->organisationMapper->getAuthorisedExaminer($aeId),
            $this->authorisationService,
            $this->urlHelper,
            $process->getFormPageTitle()
        );
        $breadcrumbs = $updateAePropertyBreadcrumbs->create();

        $vm = new UpdateAePropertyViewModel(
            $aeId, $process->getPropertyName(), $process->getFormPartial(), $process->getSubmitButtonText(), $form
        );

        $actionResult = new ActionResult();
        $actionResult->setViewModel($vm);
        $actionResult->addErrorMessages($errors);

        $actionResult->layout()->setPageTitle($process->getFormPageTitle());
        $actionResult->layout()->setPageSubTitle("Authorised Examiner");

        $actionResult->layout()->setTemplate('layout/layout-govuk.phtml');
        $actionResult->layout()->setBreadcrumbs($breadcrumbs);

        return $actionResult;
    }
}
