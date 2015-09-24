<?php
namespace OrganisationApi\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use DvsaCommonApi\Transaction\TransactionAwareInterface;
use DvsaCommonApi\Transaction\TransactionAwareTrait;
use OrganisationApi\Service\NominateRoleService;
use OrganisationApi\Service\OrganisationPositionService;
use OrganisationApi\Service\Validator\NominateRoleValidator;

/**
 * Class OrganisationPositionController
 *
 * @package OrganisationApi\Controller
 */
class OrganisationPositionController extends AbstractDvsaRestfulController implements TransactionAwareInterface
{
    use TransactionAwareTrait;

    public function __construct()
    {
        $this->setIdentifierName("organisationId");
    }

    public function get($organisationId)
    {
        $organisationId = $this->params()->fromRoute('organisationId');
        $service        = $this->getOrganisationPositionService();
        $positions      = $service->getListForOrganisation($organisationId);

        return ApiResponse::jsonOk($positions);
    }

    public function create($data)
    {
        $organisationId = $this->params()->fromRoute('organisationId');

        $validator = new NominateRoleValidator();
        $validator->validate($data);

        $nomineeId = $data['nomineeId'];
        $roleId    = $data['roleId'];

        $service              = $this->getNominateRoleService();
        $organisationPosition = $service->nominateRole($organisationId, $nomineeId, $roleId);

        return ApiResponse::jsonOk(['id' => $organisationPosition->getId()]);
    }

    /**
     * Removes organisation position of a person
     *
     * @param string $siteId
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function delete($siteId)
    {
        $organisationPositionId = $this->params()->fromRoute("positionId");
        $this->getOrganisationPositionService()->remove((int) $siteId, (int) $organisationPositionId);

        return ApiResponse::jsonOk();
    }

    /**
     *
     * @return OrganisationPositionService
     */
    private function getOrganisationPositionService()
    {
        return $this->getServiceLocator()->get(OrganisationPositionService::class);
    }

    /**
     *
     * @return NominateRoleService
     */
    private function getNominateRoleService()
    {
        return $this->getServiceLocator()->get(NominateRoleService::class);
    }
}
