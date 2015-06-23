<?php
namespace DvsaMotApi\Controller;

use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
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
use DvsaMotApi\Service\MotTestService;
use DvsaMotApi\Service\ReplacementCertificate\ReplacementCertificateService;

/**
 * Class ReplacementCertificateDraftController
 *
 * @package DvsaMotApi\Controller
 */
class ReplacementCertificateDraftController extends AbstractDvsaRestfulController implements TransactionAwareInterface
{
    use TransactionAwareTrait;

    /** @var ReplacementCertificateService $replacementCertificateService */
    private $replacementCertificateService;

    /** @var MotAuthorisationServiceInterface */
    private $authorisationService;

    /** @var CertificateCreationService $certificateCreationService */
    private $certificateCreationService;

    /** @var MotTestService $motTestService */
    private $motTestService;

    /**
     * @param ReplacementCertificateService $replacementCertificateService
     * @param MotAuthorisationServiceInterface $authorisationService
     * @param CertificateCreationService $certificateCreationService
     * @param MotTestService $motTestService
     */
    public function __construct(
        ReplacementCertificateService $replacementCertificateService,
        MotAuthorisationServiceInterface $authorisationService,
        CertificateCreationService $certificateCreationService,
        MotTestService $motTestService
    ) {
        $this->replacementCertificateService = $replacementCertificateService;
        $this->authorisationService = $authorisationService;
        $this->certificateCreationService = $certificateCreationService;
        $this->motTestService = $motTestService;
    }

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
                return $this->replacementCertificateService->createDraft($motTestNumber)->getId();
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
        $this->replacementCertificateService->updateDraft($draftId, $draftDTO);
        return ApiResponse::jsonOk();
    }

    /**
     * @param string $id
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function get($id)
    {
        $draft = $this->replacementCertificateService->getDraft($id);
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

            $motEntity = $this->replacementCertificateService->applyDraft($draftId, $data);

            $motTestNumber = $motEntity->getNumber();

            // I know it looks a bit heavy handed asking for the MOT data again when we've got
            // a perfectly good mot entity; but internally the MOT Test Service uses a private mapper
            // to return the expected array, so it's safer to pump it back through that
            $this->certificateCreationService->create(
                $motTestNumber,
                $this->motTestService->getMotTestData($motTestNumber),
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
        $draft = $this->replacementCertificateService->getDraft($draftId);
        return ApiResponse::jsonOk(ReplacementCertificateDraftDiffHelper::getDiff($draft));
    }

    /**
     * @return bool
     */
    private function hasFullRights()
    {
        return $this->authorisationService->isGranted(PermissionInSystem::CERTIFICATE_REPLACEMENT_SPECIAL_FIELDS);
    }

}
