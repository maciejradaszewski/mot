<?php

namespace DvsaMotTest\Controller;

use Application\Service\LoggedInUserManager;
use Application\Service\MotTestCertificatesService;
use DvsaCommon\Auth\PermissionAtSite;
use Core\Service\MotFrontendAuthorisationServiceInterface;
use DvsaCommon\Constants\FeatureToggle;
use DvsaCommon\Dto\Common\MotTestRecentCertificatesDto;
use DvsaMotTest\Helper\LocationSelectContainerHelper;
use DvsaMotTest\Form\EmailCertificateForm;
use Zend\Http\Headers;
use \Zend\Mvc\Application;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Zend\View\Helper\PaginationControl;
use Zend\View\Model\ViewModel;
use Zend\View\View;

/**
 * Class MotTestCertificatesController
 *
 * @package DvsaMotTest\Controller
 */
class MotTestCertificatesController extends AbstractDvsaMotTestController
{
    const ROUTE = 'mot-test-certificate-list';

    /** @var MotTestCertificatesService */
    private $certificateService;
    /** @param LoggedInUserManager $loggedInManager */
    private $loggedInManager;
    /** @param Application $applicaton */
    private $applicaton;
    /** @param LocationSelectContainerHelper $locationHelper */
    private $locationHelper;
    /** @var MotFrontendAuthorisationServiceInterface */
    private $authorisationService;

    public function __construct(
        MotTestCertificatesService $certificateService,
        LoggedInUserManager $userManager,
        Application $application,
        LocationSelectContainerHelper $locationHelper,
        MotFrontendAuthorisationServiceInterface $authorisationService
    ) {
        $this->certificateService = $certificateService;
        $this->loggedInManager = $userManager;
        $this->applicaton = $application;
        $this->locationHelper = $locationHelper;
        $this->authorisationService = $authorisationService;
    }

    public function indexAction()
    {
        $this->assertFeatureEnabled(FeatureToggle::JASPER_ASYNC);

        $this->layout('layout/layout-govuk.phtml');

        if (!$this->getIdentity()->getCurrentVts()) {
            $tester = $this->loggedInManager->getTesterData();
            // Avoid redirecting to the LocationSelectionController if the tester has only one associated site.
            if (count($tester['vtsSites']) == 1) {
                $this->loggedInManager->changeCurrentLocation($tester['vtsSites'][0]['id']);
            } else {
                $this->locationHelper->persistConfig(
                    [
                        'route' => $this->applicaton->getMvcEvent()->getRouteMatch()->getMatchedRouteName(),
                        'params' => []
                    ]
                );

                return $this->redirect()->toRoute('location-select');
            }
        }

        $vtsId = $this->getIdentity()->getCurrentVts()->getVtsId();
        $certs = $this->certificateService->getMOTCertificates($vtsId);

        return new ViewModel(
            [
                'certificates' => $certs
            ]
        );
    }

    /**
     * Streams a certificate PDF from AWS S3 to the client
     * @throws \Exception
     * @return void
     */
    public function printPdfAction()
    {
        $this->assertFeatureEnabled(FeatureToggle::JASPER_ASYNC);

        $pdfUrl = $this->certificateService->getCertificatePdfUrl(
            $this->params()->fromRoute('motRecentCertificateId')
        );

        if (($fp = fopen($pdfUrl, 'r')) !== false) {
            header('Content-Type: application/pdf');
            fpassthru($fp);
            fclose($fp);
            exit;
        }

        throw new \Exception('Unable to fetch ' . $pdfUrl);
    }

    /**
     * Streams a certificate PDF from AWS S3 to the client as an attachment
     * @throws \Exception
     * @return void
     */
    public function downloadPdfAction()
    {
        $this->assertFeatureEnabled(FeatureToggle::JASPER_ASYNC);

        $vin = $this->params()->fromRoute('vin');
        $status = $this->params()->fromRoute('status');
        $motRecentCertificateId = $this->params()->fromRoute('motRecentCertificateId');
        $pdfUrl = $this->certificateService->getCertificatePdfUrl(
            $motRecentCertificateId
        );

        if (($fp = fopen($pdfUrl, 'r')) !== false) {
            $pdfName = "$vin-$status-$motRecentCertificateId";
            header('Content-Type: application/pdf');
            header("Content-Disposition: attachment; filename=\"$pdfName.pdf\"");
            header("Content-Type: application/force-download");
            header("Content-Transfer-Encoding: binary");
            fpassthru($fp);
            fclose($fp);
            exit;
        }

        throw new \Exception('Unable to fetch ' . $pdfUrl);
    }

    public function emailCertificateAction()
    {
        $this->assertFeatureEnabled(FeatureToggle::JASPER_ASYNC);

        $this->layout('layout/layout-govuk.phtml');

        $certificateId = (int)$this->params('certificateId');
        /** @var MotTestRecentCertificatesDto $recentCertificateDto */
        $recentCertificateDto = $this->certificateService->getMOTCertificate($certificateId);

        $form = new EmailCertificateForm();
        $form->setData([
            "firstName" => $recentCertificateDto->getRecipientFirstName(),
            "familyName" => $recentCertificateDto->getRecipientFamilyName(),
            "email" => $recentCertificateDto->getRecipientEmailAddress(),
            "retypeEmail" => $recentCertificateDto->getRecipientEmailAddress(),
        ]);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                $data = [
                    "firstName" => $form->get("firstName")->getValue(),
                    "familyName" => $form->get("familyName")->getValue(),
                    "email" => $form->get("email")->getValue(),
                ];

                if ($this->certificateService->saveEmailCertificate($certificateId, $data)) {
                    return $this->redirect()->toRoute('mot-test-certificate-email-confirmation',
                        ['certificateId' => $certificateId]);
                }

                return $this->redirect()->toRoute('mot-test-certificate-email-error');
            }
        }

        return [
            'form' => $form,
            'recentCertificateDto' => $recentCertificateDto
        ];
    }

    public function emailConfirmationAction()
    {
        $this->assertFeatureEnabled(FeatureToggle::JASPER_ASYNC);

        $this->layout('layout/layout-govuk.phtml');

        $certificateId = (int)$this->params('certificateId');
        /** @var MotTestRecentCertificatesDto $recentCertificateDto */
        $recentCertificateDto = $this->certificateService->getMOTCertificate($certificateId);

        return ["recentCertificateDto" => $recentCertificateDto];
    }

    public function emailErrorAction()
    {
        $this->assertFeatureEnabled(FeatureToggle::JASPER_ASYNC);

        $this->authorisationService->isGrantedAtAnySite(PermissionAtSite::RECENT_CERTIFICATE_PRINT);

        $this->layout('layout/layout-govuk.phtml');

        return [];
    }
}
