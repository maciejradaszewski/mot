<?php

namespace Dashboard\Controller;

use Core\Controller\AbstractAuthActionController;
use Core\Service\MotFrontendIdentityProviderInterface;
use Dashboard\Authorisation\ViewTradeRolesAssertion;
use Dashboard\ViewModel\UserTradeRolesViewModel;
use Dashboard\Service\TradeRolesAssociationsService;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use Zend\View\Model\ViewModel;

class UserTradeRolesController extends AbstractAuthActionController
{
    private $identityProvider;

    private $tradeRolesAssociationsService;

    private $viewTradeRolesAssertion;

    /**
     * @var AuthorisationServiceInterface
     */
    private $authorisationService;

    public function __construct(
        MotFrontendIdentityProviderInterface $identityProvider,
        TradeRolesAssociationsService $tradeRolesAssociationsService,
        ViewTradeRolesAssertion $viewTradeRolesAssertion,
        AuthorisationServiceInterface $authorisationService
    )
    {
        $this->identityProvider = $identityProvider;
        $this->tradeRolesAssociationsService = $tradeRolesAssociationsService;
        $this->viewTradeRolesAssertion = $viewTradeRolesAssertion;
        $this->authorisationService = $authorisationService;
    }

    public function indexAction()
    {
        $personId = (int)$this->params()->fromRoute('id');

        $this->viewTradeRolesAssertion->assertGratedViewProfileTradeRolesPage($personId);

        $rolesAndAssociations = $this->tradeRolesAssociationsService->getRolesAndAssociations($personId);

        $this->setPageTitle('Roles and Associations');

        $personIsViewingOwnProfile = false;
        if ($personId == $this->identityProvider->getIdentity()->getUserId()) {
            $this->setPageSubTitle('Your profile');
            $personIsViewingOwnProfile = true;
        } else {
            $this->setPageSubTitle('User profile');
        }

        if (!$rolesAndAssociations) {
            if ($personId == $this->identityProvider->getIdentity()->getUserId()) {
                $this->setPageLede("You don't have any roles and associations");
            } else {
                $this->setPageLede("User doesn't have any active role associations");
            }
        }

        $this->layout('layout/layout-govuk.phtml');

        $vm = new UserTradeRolesViewModel($this->authorisationService);
        $vm->setRolesAndAssociations($rolesAndAssociations);
        $vm->setPersonId((int)$this->params()->fromRoute('id'));
        $vm->setPersonIsViewingOwnProfile($personIsViewingOwnProfile);

        return [
            'userTradeRolesViewModel' => $vm,
        ];
    }
}
