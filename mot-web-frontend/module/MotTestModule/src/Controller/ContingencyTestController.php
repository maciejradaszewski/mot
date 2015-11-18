<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\MotTestModule\Controller;

use Application\Service\ContingencySessionManager;
use Application\Service\LoggedInUserManager;
use Dashboard\Controller\UserHomeController;
use DateTimeImmutable;
use Dvsa\Mot\Frontend\MotTestModule\Validation\ContingencyTestValidator;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Dto\MotTesting\ContingencyTestDto;
use DvsaCommon\Enum\EmergencyReasonCode;
use DvsaCommon\HttpRestJson\Exception\GeneralRestException;
use DvsaCommon\HttpRestJson\Exception\RestApplicationException;
use DvsaCommon\HttpRestJson\Exception\ValidationException as ApiValidationException;
use DvsaCommon\UrlBuilder\UrlBuilder;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommon\Validation\ValidationException;
use DvsaMotTest\Controller\AbstractDvsaMotTestController;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;

/**
 * ContingencyTest Controller.
 */
class ContingencyTestController extends AbstractDvsaMotTestController
{
    /**
     * @var ContingencyTestValidator
     */
    private $contingencyTestValidator;

    /**
     * ContingencyTestController constructor.
     *
     * @param ContingencyTestValidator $contingencyTestValidator
     */
    public function __construct(ContingencyTestValidator $contingencyTestValidator)
    {
        $this->contingencyTestValidator = $contingencyTestValidator;
    }

    /**
     * This action display the contingency form.
     *
     * @throws \DvsaCommon\Auth\NotLoggedInException
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        if (!$this->getAuthorizationService()->isGranted(PermissionInSystem::EMERGENCY_TEST_READ)) {
            return $this->redirect()->toRoute(UserHomeController::ROUTE);
        }

        /* @var ContingencySessionManager $contingencySessionManager */
        $contingencySessionManager = $this->serviceLocator->get(ContingencySessionManager::class);

        /* @var Request $request */
        $request = $this->getRequest();

        /* @var LoggedInUserManager $loggedInManager */
        $loggedInManager = $this->getServiceLocator()->get('LoggedInUserManager');
        $loggedInVts     = $loggedInManager->getAllVts();

        $dto = new ContingencyTestDto();
        $validationSummary = null;
        $inlineMessages = null;

        if ($request->isPost()) {
            $data = $this->getRequest()->getPost()->getArrayCopy();

            try {
                // Validate our POST data
                $dto = $this->dtoFromRequest($data);
                $validationResult = $this->contingencyTestValidator->validate($data);
                if (false === $validationResult->isValid()) {
                    throw new ValidationException($validationResult);
                }

                $apiUrl = UrlBuilder::contingency()->toString();

                $apiResult = $this->getRestClient()->post($apiUrl, DtoHydrator::dtoToJson($dto));

                $currentVts = $this->getIdentity()->getCurrentVts();
                if (!$currentVts || $currentVts->getVtsId() != (int) $dto->getSiteId()) {
                    $loggedInManager->getTesterData();
                    $site = (int) $dto->getSiteId();
                    $loggedInManager->changeCurrentLocation($site);
                }

                $contingencySessionManager->createContingencySession($dto, $apiResult['data']['emergencyLogId']);

                return $this->redirect()->toRoute('vehicle-search', [], ['query' => ['contingency' => 1]]);
            } catch (ApiValidationException $e) {
                $inlineMessages = $e->getValidationMessages();
                $validationSummary = $this->getValidationSummary($inlineMessages);
            } catch (ValidationException $e) {
                $inlineMessages = $e->getInlineMessages();
                $validationSummary = $this->getValidationSummary($e->getInlineMessages());
            } catch (RestApplicationException $e) {
                $this->addErrorMessages($e->getDisplayMessages());
            } catch (GeneralRestException $e) {
                $this->addErrorMessages($e->getMessage());
            }
        }

        $this->enableGdsLayout('Record contingency test', 'MOT testing');

        return $this->createViewModel('contingency-test/index.phtml', [
            'dto'                    => $dto,
            'sites'                  => $loggedInVts,
            'contingencyReasonCodes' => $this->getContingencyReasonCodes(),
            'inlineMessages'         => $inlineMessages,
            'validationSummary'      => $validationSummary,
        ]);
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function errorAction()
    {
        $this->enableGdsLayout('Contingency test error', 'MOT testing');

        return $this->createViewModel('contingency-test/error.phtml', []);
    }

    /**
     * Extracts a DTO from the request data.
     *
     * @param array $data
     *
     * @return ContingencyTestDto
     */
    protected function dtoFromRequest(array $data)
    {
        $dto = new ContingencyTestDto();
        $dto->setSiteId(ArrayUtils::tryGet($data, 'site_id'));
        $dto->setPerformedAt(DateTimeImmutable::createFromFormat('Y-m-d g:ia', sprintf('%d-%02d-%02d %d:%02d%s',
            trim($data['performed_at_year']), trim($data['performed_at_month']), trim($data['performed_at_day']),
            trim($data['performed_at_hour']), trim($data['performed_at_minute']), trim($data['performed_at_am_pm']))));
        $dto->setReasonCode(ArrayUtils::tryGet($data, 'reason_code'));
        $dto->setOtherReasonText(ArrayUtils::tryGet($data, 'other_reason_text'));
        $dto->setContingencyCode(trim($data['contingency_code']));

        return $dto;
    }

    /**
     * Sets the GDS layout.
     *
     * @param string $title
     * @param string $subtitle
     */
    private function enableGdsLayout($title, $subtitle)
    {
        $this->layout('layout/layout-govuk.phtml');
        $this
            ->layout()
            ->setVariable('pageTitle', $title)
            ->setVariable('pageSubTitle', $subtitle);
    }

    /**
     * @param $template
     * @param array $variables
     *
     * @return ViewModel
     */
    private function createViewModel($template, array $variables)
    {
        $viewModel = new ViewModel();
        $viewModel->setTemplate($template);
        $viewModel->setVariables($variables);

        return $viewModel;
    }

    /**
     * @return array
     */
    private function getContingencyReasonCodes()
    {
        return [
            'systemOutage'         => EmergencyReasonCode::SYSTEM_OUTAGE,
            'communicationProblem' => EmergencyReasonCode::COMMUNICATION_PROBLEM,
            'other'                => EmergencyReasonCode::OTHER,
        ];
    }

    /**
     * @param array $messages
     *
     * @return array
     */
    private function getValidationSummary(array $messages)
    {
        $map = [
            ContingencyTestValidator::FIELDSET_SITE              => 'Location where the test was performed',
            ContingencyTestValidator::FIELDSET_DATE              => 'Date the test was performed',
            ContingencyTestValidator::FIELDSET_TIME              => 'Time the test was performed',
            ContingencyTestValidator::FIELDSET_REASON            => 'Reason for contingency testing',
            ContingencyTestValidator::FIELDSET_OTHER_REASON_TEXT => 'Reason for contingency testing',
            ContingencyTestValidator::FIELDSET_CONTINGENCY_CODE  => 'Contingency code',
        ];

        foreach (array_keys($messages) as $k) {
            if (!array_key_exists($k, $map)) {
                continue;
            }

            $messages[$k] = $map[$k] . ' - ' . $messages[$k];
        }

        return $messages;
    }
}
