<?php
namespace DvsaMotApi\Controller;

use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use DvsaCommonApi\Service\Exception\RequiredFieldException;
use DvsaCommonApi\Transaction\TransactionAwareInterface;
use DvsaCommonApi\Transaction\TransactionAwareTrait;
use DvsaMotApi\Dto\ReplacementCertificateDraftChangeDTO;
use DvsaMotApi\Helper\ReplacementCertificate\ReplacementCertificateDraftDiffHelper;
use DvsaMotApi\Helper\ReplacementCertificate\ReplacementCertificateDraftMappingHelper;
use DvsaMotApi\Service\CertificateCreationService;
use DvsaMotApi\Service\ReplacementCertificate\ReplacementCertificateService;

/**
 * Class ReplacementCertificateDraftController
 *
 * @package DvsaMotApi\Controller
 */
class ReplacementCertificateDraftController extends AbstractDvsaRestfulController implements TransactionAwareInterface
{
    use TransactionAwareTrait;

    /**
     * @param array $data
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function create($data)
    {
        RequiredFieldException::CheckIfRequiredFieldsNotEmpty(['motTestNumber'], $data);
        $motTestNumber = $data['motTestNumber'];

        $draftId = $this->inTransaction(
            function () use (&$motTestNumber) {
                return $this->getReplacementCertificateService()->createDraft($motTestNumber)->getId();
            }
        );

        return ApiResponse::jsonOk(['id' => $draftId]);
    }

    /**
     * @param string $draftId
     * @param array $data
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function update($draftId, $data)
    {
        $draftDTO = ReplacementCertificateDraftChangeDTO::fromDataArray($data);
        $this->getReplacementCertificateService()->updateDraft($draftId, $draftDTO);
        return ApiResponse::jsonOk();
    }

    /**
     * @param string $id
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function get($id)
    {
        $draft = $this->getReplacementCertificateService()->getDraft($id);
        $data = ReplacementCertificateDraftMappingHelper::toJsonArray($draft, $this->hasFullRights());

        return ApiResponse::jsonOk($data);
    }

    /**
     * @return \Zend\View\Model\JsonModel
     * @throws \DvsaCommonApi\Service\Exception\BadRequestException
     */
    public function applyAction()
    {
        if ($this->getRequest()->isPost()) {
            $draftId = $this->params()->fromRoute("id");
            $data = $this->processBodyContent($this->getRequest());

            $motEntity = $this->getReplacementCertificateService()->applyDraft($draftId, $data);

            $motTestNumber = $motEntity->getNumber();

            // I know it looks a bit heavy handed asking for the MOT data again when we've got
            // a perfectly good mot entity; but internally the MOT Test Service uses a private mapper
            // to return the expected array, so it's safer to pump it back through that
            $this->getCertificateService()->create(
                $motTestNumber,
                $this->getMotTestService()->getMotTestData($motTestNumber),
                $this->getUserId()
            );

            return ApiResponse::jsonOk();
        }
        return $this->returnMethodNotAllowedResponseModel();
    }

    /**
     * @return \Zend\View\Model\JsonModel
     */
    public function diffAction()
    {
        $draftId = $this->params()->fromRoute("id");
        $draft = $this->getReplacementCertificateService()->getDraft($draftId);
        return ApiResponse::jsonOk(ReplacementCertificateDraftDiffHelper::getDiff($draft));
    }

    /**
     * @return bool
     */
    private function hasFullRights()
    {
        return $this->getAuthorizationService()->isGranted(PermissionInSystem::CERTIFICATE_REPLACEMENT_SPECIAL_FIELDS);
    }

    /**
     * @return ReplacementCertificateService
     */
    private function getReplacementCertificateService()
    {
        return $this->serviceLocator->get("ReplacementCertificateService");
    }

    /**
     * @return AuthorisationServiceInterface
     */
    private function getAuthorizationService()
    {
        return $this->serviceLocator->get("DvsaAuthorisationService");
    }

    /**
     * @return \DvsaMotApi\Service\CertificateCreationService
     */
    private function getCertificateService()
    {
        return $this->serviceLocator->get(CertificateCreationService::class);
    }

    /**
     * @return \DvsaMotApi\Service\MotTestService
     */
    private function getMotTestService()
    {
        return $this->serviceLocator->get('MotTestService');
    }
}
