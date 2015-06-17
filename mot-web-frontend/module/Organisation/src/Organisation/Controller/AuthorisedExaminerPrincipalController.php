<?php

namespace Organisation\Controller;

use Core\Controller\AbstractAuthActionController;
use DvsaCommon\HttpRestJson\Exception\RestApplicationException;
use DvsaCommon\HttpRestJson\Exception\ValidationException;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilderWeb;
use Organisation\Traits\OrganisationServicesTrait;
use SebastianBergmann\Exporter\Exception;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Core\Service\MotFrontendAuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionAtOrganisation;

/**
 * Class AuthorisedExaminerPrincipalController
 *
 * @package Organisation\Controller
 */
class AuthorisedExaminerPrincipalController extends AbstractAuthActionController
{
    use OrganisationServicesTrait;

    /**
     * @var MotFrontendAuthorisationServiceInterface
     */
    private $authorisationService;

    public function __construct(MotFrontendAuthorisationServiceInterface $authorisationService)
    {
        $this->authorisationService = $authorisationService;
    }

    public function indexAction()
    {
        $authorisedExaminerId = $this->params()->fromRoute('id');
        $permission = PermissionAtOrganisation::AUTHORISED_EXAMINER_PRINCIPAL_CREATE;
        $this->authorisationService->assertGrantedAtOrganisation($permission, $authorisedExaminerId);

        $form = [];

        $urlAE = AuthorisedExaminerUrlBuilderWeb::of($authorisedExaminerId);

        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $postData = $this->getRequest()->getPost()->toArray();
                $form = $postData;
                $mapperFactory = $this->getMapperFactory();
                $principalId = $mapperFactory->Person->createPrincipalsForOrganisation(
                    $authorisedExaminerId, $postData
                );

                $principal = $mapperFactory->Person->getById($principalId);
                $this->addInfoMessages($principal->getFullName() . ' has been added as AEP');

                return $this->redirect()->toUrl($urlAE);
            } catch (ValidationException $ve) {
                $this->addErrorMessages($ve->getDisplayMessages());
            }
        }

        return [
            'values'        => $form,
            'cancelRoute' => $urlAE,
        ];
    }

    public function removeConfirmationAction()
    {
        $authorisedExaminerId = $this->params()->fromRoute('id');
        $permission = PermissionAtOrganisation::AUTHORISED_EXAMINER_PRINCIPAL_REMOVE;
        $this->authorisationService->assertGrantedAtOrganisation($permission, $authorisedExaminerId);

        $principalId = $this->params()->fromRoute('principalId');

        $mapperFactory = $this->getMapperFactory();
        $authorisedExaminer = $mapperFactory->Organisation->getAuthorisedExaminer($authorisedExaminerId);
        $principal = $mapperFactory->Person->getById($principalId);

        $urlAE = AuthorisedExaminerUrlBuilderWeb::of($authorisedExaminerId);

        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $mapperFactory->Person->removePrincipalsForOrganisation(
                    $authorisedExaminerId, $principalId
                );

                $this->addInfoMessages($principal->getFullName() . ' has been removed as AEP');

                return $this->redirect()->toUrl($urlAE);
            } catch (RestApplicationException $e) {
                $this->addErrorMessages($e->getDisplayMessages());
            }

            return $this->redirect()->toUrl($urlAE);
        }

        return [
            'principal'          => $principal,
            'authorisedExaminer' => $authorisedExaminer,
            'cancelRoute'        => $urlAE,
        ];
    }
}
