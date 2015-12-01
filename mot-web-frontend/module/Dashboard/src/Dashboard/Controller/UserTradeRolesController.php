<?php

namespace Dashboard\Controller;

use Core\Catalog\BusinessRole\BusinessRole;
use Core\Catalog\EnumCatalog;
use Core\Controller\AbstractAuthActionController;
use Core\Service\MotAuthorizationRefresherInterface;
use Core\Service\MotFrontendAuthorisationServiceInterface;
use Core\Service\MotFrontendIdentityProviderInterface;
use Dashboard\Authorisation\ViewTradeRolesAssertion;
use Dashboard\Service\PersonTradeRoleSorterService;
use Dashboard\Service\TradeRolesAssociationsService;
use Dashboard\ViewModel\RemoveRoleViewModel;
use Dashboard\ViewModel\UserTradeRolesViewModel;
use Dvsa\Mot\Frontend\AuthenticationModule\Model\Identity;
use DvsaClient\Mapper\BusinessPositionMapperInterface;
use DvsaClient\Mapper\OrganisationPositionMapper;
use DvsaClient\Mapper\SitePositionMapper;
use DvsaCommon\ApiClient\Person\PersonTradeRoles\Dto\PersonTradeRoleDto;
use DvsaCommon\ApiClient\Person\PersonTradeRoles\PersonTradeRolesApiResource;
use DvsaCommon\Enum\RoleCode;
use DvsaCommon\HttpRestJson\Exception\ValidationException;
use DvsaCommon\Utility\ArrayUtils;
use Zend\View\Model\ViewModel;

class UserTradeRolesController extends AbstractAuthActionController
{
    const ROUTE_REMOVE_AE_ROLE = 'user-home/profile/byId/remove-ae-role';
    const ROUTE_REMOVE_VTS_ROLE = 'user-home/profile/byId/remove-vts-role';
    const ROUTE_TRADE_ROLES = 'user-home/profile/byId/trade-roles';

    private $identityProvider;
    private $tradeRolesAssociationsService;
    private $viewTradeRolesAssertion;
    private $authorisationService;
    private $organisationPositionMapper;
    private $sitePositionMapper;
    private $personTradeRolesApiResource;
    private $catalog;
    private $authorisationRefresher;

    /**
     * @var PersonTradeRoleSorterService
     */
    private $personTradeRoleSorter;

    public function __construct(
        MotFrontendIdentityProviderInterface $identityProvider,
        TradeRolesAssociationsService $tradeRolesAssociationsService,
        ViewTradeRolesAssertion $viewTradeRolesAssertion,
        MotFrontendAuthorisationServiceInterface $authorisationService,
        OrganisationPositionMapper $organisationPositionMapper,
        SitePositionMapper $sitePositionMapper,
        PersonTradeRolesApiResource $personTradeRolesApiResource,
        EnumCatalog $catalog,
        MotAuthorizationRefresherInterface $authorisationRefresher,
        PersonTradeRoleSorterService $personTradeRoleSorter
    )
    {
        $this->identityProvider = $identityProvider;
        $this->tradeRolesAssociationsService = $tradeRolesAssociationsService;
        $this->viewTradeRolesAssertion = $viewTradeRolesAssertion;
        $this->authorisationService = $authorisationService;
        $this->organisationPositionMapper = $organisationPositionMapper;
        $this->sitePositionMapper = $sitePositionMapper;
        $this->personTradeRolesApiResource = $personTradeRolesApiResource;
        $this->catalog = $catalog;
        $this->authorisationRefresher = $authorisationRefresher;
        $this->personTradeRoleSorter = $personTradeRoleSorter;
    }

    public function indexAction()
    {
        $personId = (int)$this->params()->fromRoute('id');

        $this->viewTradeRolesAssertion->assertGratedViewProfileTradeRolesPage($personId);

        $tradeRoles = $this->personTradeRolesApiResource->getRoles($personId);

        $this->setPageTitle('Roles and Associations');

        $personIsViewingOwnProfile = false;
        if ($personId == $this->identityProvider->getIdentity()->getUserId()) {
            $this->setPageSubTitle('Your profile');
            $personIsViewingOwnProfile = true;
        } else {
            $this->setPageSubTitle('User profile');
        }

        if (!$tradeRoles) {
            if ($personId == $this->identityProvider->getIdentity()->getUserId()) {
                $this->setPageLede("You don't have any roles and associations");
            } else {
                $this->setPageLede("User doesn't have any active role associations");
            }
        }

        $this->layout('layout/layout-govuk.phtml');

        $urlHelper = $this->getServiceLocator()->get('ViewHelperManager')->get('url');

        $vm = new UserTradeRolesViewModel($this->authorisationService,
            $this->personTradeRoleSorter->sortTradeRoles($tradeRoles),
            $this->catalog->businessRole(),
            $urlHelper
        );
        $vm->setPersonId((int)$this->params()->fromRoute('id'));
        $vm->setPersonIsViewingOwnProfile($personIsViewingOwnProfile);

        return [
            'userTradeRolesViewModel' => $vm,
        ];
    }

    protected function removeRole(BusinessPositionMapperInterface $positionMapper, $roleType)
    {
        $positionId = $this->params()->fromRoute('positionId');
        $entityId = $this->params()->fromRoute('entityId');
        $personId = $this->params()->fromRoute('id');

        $this->viewTradeRolesAssertion->assertGrantedViewRemoveRolePage($personId);

        $this->layout('layout/layout-govuk.phtml');
        $this->setPageTitle('Remove role');
        $this->setPageSubTitle('Your profile');

        $personTradeRole = $this->getPersonTradeRole($positionId, $personId);

        if ($this->getRequest()->isPost()) {
            try {
                $positionMapper->deletePosition($entityId, $positionId);
                $this->addSuccessMessage('Role removed successfully');

                $this->clearCurrentVtsIfNeeded($personTradeRole);
                $this->authorisationRefresher->refreshAuthorization();
            } catch (ValidationException $e) {
                $this->addErrorMessages($e->getDisplayMessages());
            }

            return $this->redirect()->toRoute(self::ROUTE_TRADE_ROLES, ['id' => $personId]);
        }

        if (!$personTradeRole) {
            return $this->redirect()->toRoute(self::ROUTE_TRADE_ROLES, ['id' => $personId]);
        }

        $removeRoleViewModel = (new RemoveRoleViewModel())
            ->setEntityId($personTradeRole->getWorkplaceId())
            ->setPositionId($positionId)
            ->setPersonId($personId)
            ->setRoleName($this->catalog->businessRole()->getByCode($personTradeRole->getRoleCode())->getName())
            ->setEntityName($personTradeRole->getWorkplaceName());

        $viewModel = new ViewModel([
            'viewModel' => $removeRoleViewModel,
        ]);
        $viewModel->setTemplate('/dashboard/user-trade-roles/remove-role.phtml');

        return $viewModel;
    }

    private function clearCurrentVtsIfNeeded(PersonTradeRoleDto $personTradeRole)
    {
        $roleType = $this->catalog->businessRole()->getByCode($personTradeRole->getRoleCode())->getType();

        if ($roleType === BusinessRole::SITE_TYPE) {
            /** @var Identity $identity */
            $identity = $this->getIdentity();
            $personId = $this->params()->fromRoute('id');
            if ($personId == $identity->getUserId()) {
                $currentVts = $identity->getCurrentVts();
                if ($currentVts) {
                    if ($currentVts->getVtsId() == $personTradeRole->getWorkplaceId()) {
                        $identity->clearCurrentVts();
                    }
                }
            }
        }
    }

    public function removeVtsRoleAction()
    {
        return $this->removeRole($this->sitePositionMapper, BusinessRole::SITE_TYPE);
    }

    public function removeAeRoleAction()
    {
        return $this->removeRole($this->organisationPositionMapper, BusinessRole::ORGANISATION_TYPE);
    }

    /**
     * @param $tradeRoleId
     * @param $personId
     * @return PersonTradeRoleDto
     */
    public function getPersonTradeRole($tradeRoleId, $personId)
    {
        $personTradeRoles = $this->personTradeRolesApiResource->getRoles($personId);

        return ArrayUtils::firstOrNull(
            $personTradeRoles,
            function (PersonTradeRoleDto $tradeRole) use ($tradeRoleId) {
                return $tradeRole->getPositionId() == $tradeRoleId;
            });
    }
}