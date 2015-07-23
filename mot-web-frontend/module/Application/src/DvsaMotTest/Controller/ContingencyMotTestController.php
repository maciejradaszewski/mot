<?php
namespace DvsaMotTest\Controller;

use Application\Service\ContingencySessionManager;
use Application\Service\LoggedInUserManager;
use Dashboard\Controller\UserHomeController;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Dto\MotTesting\ContingencyMotTestDto;
use DvsaCommon\HttpRestJson\Exception\GeneralRestException;
use DvsaCommon\HttpRestJson\Exception\RestApplicationException;
use DvsaCommon\UrlBuilder\UrlBuilder;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Utility\DtoHydrator;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;

/**
 * Class ContingencyMotTestController.
 */
class ContingencyMotTestController extends AbstractDvsaMotTestController
{
    /**
     * This action display the contingency form.
     *
     * @throws \DvsaCommon\Auth\NotLoggedInException
     *
     * @return Response|ViewModel
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

        $dto = new ContingencyMotTestDto();

        if ($request->isPost()) {
            $dto = $this->dtoFromRequest();

            $apiUrl = UrlBuilder::contingency()->toString();
            try {
                $apiResult = $this->getRestClient()->post(
                    $apiUrl, DtoHydrator::dtoToJson($dto)
                );

                $currentVts = $this->getIdentity()->getCurrentVts();
                if (!$currentVts || $currentVts->getVtsId() != (int) $dto->getSiteId()) {
                    $loggedInManager->getTesterData();
                    $site = (int) $dto->getSiteId();
                    $loggedInManager->changeCurrentLocation($site);
                }

                $contingencySessionManager->createContingencySession($dto, $apiResult['data']['emergencyLogId']);

                return $this->redirect()->toRoute(
                    $dto->getTestType() == 'normal' ? 'vehicle-search' : 'retest-vehicle-search',
                    [],
                    ['query' => ['contingency' => 1]]
                );
            } catch (RestApplicationException $e) {
                $this->addErrorMessages($e->getDisplayMessages());
            } catch (GeneralRestException $e) {
                $this->addErrorMessages($e->getMessage());
            }
        }

        return new ViewModel(
            [
                'dto'         => $dto,
                'loggedInVts' => $loggedInVts,
            ]
        );
    }

    /**
     * Extracts a DTO from the request.
     *
     * @return ContingencyMotTestDto
     */
    protected function dtoFromRequest()
    {
        $data = $this->getRequest()->getPost()->getArrayCopy();

        $dto = new ContingencyMotTestDto;
        $dto->setTestedByWhom(trim($data['radio-test-who-group']));
        $dto->setSiteId(trim($data['radio-site-group']));
        if (isset($data['radio-test-type-group'])) {
            $dto->setTestType(trim($data['radio-test-type-group']));
        }
        $dto->setContingencyCode(trim($data['ct-code']));
        $dto->setTesterCode(''); // VM-7493: removed ==> $data['testerNumber']);
        $dto->setPerformedAt(
            trim($data['dateTestYear']) . '-' . trim($data['dateTestMonth']) . '-' . trim($data['dateTestDay'])
        );
        $dto->setDateYear(trim($data['dateTestYear']));
        $dto->setDateMonth(trim($data['dateTestMonth']));
        $dto->setDateDay(trim($data['dateTestDay']));

        $reasonCode = ArrayUtils::tryGet($data, 'radio-reason-group');
        $dto->setReasonCode($reasonCode);
        $dto->setReasonText(ArrayUtils::tryGet($data, 'other-reasons'));

        return $dto;
    }

    /**
     * This action display a error page due to a contingency error
     * It offers a link to go back to the contingency form.
     *
     * @return ViewModel
     */
    public function errorAction()
    {
        return new ViewModel();
    }
}
