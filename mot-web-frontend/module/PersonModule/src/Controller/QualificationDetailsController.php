<?php

namespace Dvsa\Mot\Frontend\PersonModule\Controller;

use Core\Action\ActionResult;
use Core\Controller\AbstractAuthActionController;
use Core\TwoStepForm\EditStepAction;
use Core\TwoStepForm\ReviewStepAction;
use Dvsa\Mot\Frontend\PersonModule\Action\QualificationDetailsAction;
use Dvsa\Mot\Frontend\PersonModule\Breadcrumbs\QualificationDetailsBreadcrumbs;
use Dvsa\Mot\Frontend\PersonModule\Model\QualificationDetailsAddProcess;
use Dvsa\Mot\Frontend\PersonModule\Model\QualificationDetailsContext;
use Dvsa\Mot\Frontend\PersonModule\Model\QualificationDetailsAbstractProcess;
use Dvsa\Mot\Frontend\PersonModule\Model\QualificationDetailsEditProcess;
use Dvsa\Mot\Frontend\PersonModule\Service\RemoveCertificateDetailsService;
use DvsaClient\MapperFactory;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use Zend\View\Model\ViewModel;

class QualificationDetailsController extends AbstractAuthActionController implements AutoWireableInterface
{
    private $viewAction;
    private $editStepAction;
    private $reviewStepAction;
    private $qualificationDetailsAddProcess;
    private $qualificationDetailsEditProcess;

    private $removeCertificateDetailsService;

    private $breadcrumbs;

    public function __construct(
        QualificationDetailsAction $viewAction,
        EditStepAction $editStepAction,
        ReviewStepAction $reviewStepAction,
        QualificationDetailsAddProcess $qualificationDetailsAddProcess,
        QualificationDetailsEditProcess $qualificationDetailsEditProcess,
        RemoveCertificateDetailsService $removeCertificateDetailsService,
        QualificationDetailsBreadcrumbs $breadcrumbs
    )
    {
        $this->viewAction = $viewAction;
        $this->editStepAction = $editStepAction;
        $this->reviewStepAction = $reviewStepAction;
        $this->qualificationDetailsAddProcess = $qualificationDetailsAddProcess;
        $this->qualificationDetailsEditProcess = $qualificationDetailsEditProcess;
        $this->removeCertificateDetailsService = $removeCertificateDetailsService;
        $this->breadcrumbs = $breadcrumbs;
    }

    public function viewAction()
    {
        $personId = $this->params()->fromRoute('id');

        $actionResult = $this->viewAction->execute($personId, $this);

        return $this->applyActionResult($actionResult);
    }

    private function runQualificationDetailsProcessAction(
        QualificationDetailsAbstractProcess $process, $action, $template)
    {
        $isPost = $this->getRequest()->isPost();
        $context = new QualificationDetailsContext(
            $this->params()->fromRoute('id'),
            $this->params()->fromRoute('group'),
            $this
        );
        $formUuid = $this->params()->fromRoute('formUuid');
        if(empty($formUuid)) {
            $formUuid = $this->params()->fromQuery('formUuid');
        }

        //todo eh
        if(get_class($action) == EditStepAction::class) {
            $formData = $this->getRequest()->getPost()->getArrayCopy();
            $actionResult = $action->execute($isPost, $process, $context, $formUuid, $formData);
        } elseif(get_class($action) == ReviewStepAction::class) {
            $actionResult = $action->execute($isPost, $process, $context, $formUuid);
        }

        //todo if there's no redirect
        if(get_class($actionResult) == ActionResult::class) {
            $actionResult->setTemplate($template);
        }

        return $this->applyActionResult($actionResult);

    }

    public function addAction()
    {
        return $this->runQualificationDetailsProcessAction($this->qualificationDetailsAddProcess,
            $this->editStepAction, 'qualification-details/add-or-edit'
        );
    }

    public function addReviewAction()
    {
        return $this->runQualificationDetailsProcessAction($this->qualificationDetailsAddProcess,
            $this->reviewStepAction, 'qualification-details/review'
        );
    }

    public function editAction()
    {
        return $this->runQualificationDetailsProcessAction($this->qualificationDetailsEditProcess,
            $this->editStepAction, 'qualification-details/add-or-edit'
        );
    }

    public function editReviewAction()
    {
        return $this->runQualificationDetailsProcessAction($this->qualificationDetailsEditProcess,
            $this->reviewStepAction, 'qualification-details/review'
        );
    }

    public function removeAction()
    {
        $personId = (int) $this->params()->fromRoute('id');
        $group = $this->params()->fromRoute('group');

        $breadcrumbs = $this->breadcrumbs->getBreadcrumbs($personId, $this, "Remove certificate");

        $this->removeCertificateDetailsService->setBreadcrumbs($breadcrumbs);

        $result = $this->removeCertificateDetailsService->process(
            $personId,
            $group,
            $this->breadcrumbs->getRoute(),
            $this->getRequest()->isPost()
        );

        return $this->applyActionResult($result);
    }
}
