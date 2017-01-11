<?php

namespace Core\TwoStepForm;

use Core\Action\ActionResult;
use Core\Action\NotFoundActionResult;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use Zend\Form\Form;
use Core\TwoStepForm\TwoStepFormContainer;
use Core\TwoStepForm\TwoStepProcessInterface;
use Core\TwoStepForm\SingleStepProcessInterface;
use Core\TwoStepForm\FormContextInterface;
use Zend\View\Model\ViewModel;

final class ConfirmationStepAction implements AutoWireableInterface
{
    private $formContainer;
    private $authorisationService;

    public function __construct(
        TwoStepFormContainer $formContainer,
        MotAuthorisationServiceInterface $authorisationService
    )
    {
        $this->formContainer = $formContainer;
        $this->authorisationService = $authorisationService;
    }

    /**
     * @param $isPost
     * @param SingleStepProcessInterface $process
     * @param FormContextInterface $context
     * @return ActionResult
     * @throws UnauthorisedException
     */
    public function execute($isPost, SingleStepProcessInterface $process, FormContextInterface $context)
    {
        $process->setContext($context);

        //$this->assertGranted($process);

        if (!$process instanceof TwoStepProcessInterface) {
            return new NotFoundActionResult();
        }

        if ($isPost) {
            return $process->redirectToStartPage();
        }

        return $this->buildActionResult($process);
    }

    private function assertGranted(SingleStepProcessInterface $process)
    {
        if (!$process->isAuthorised($this->authorisationService)) {
            throw new UnauthorisedException("Not authorised to view the confirmation page");
        }
    }

    /**
     * @param TwoStepProcessInterface $process
     * @return ActionResult
     */
    protected function buildActionResult(TwoStepProcessInterface $process)
    {
        $breadcrumbs = $process->getBreadcrumbs($this->authorisationService);

        $vm = new ViewModel();

        $variables = $process->populateConfirmationPageVariables();

        $vm->setVariables($variables);

        $actionResult = new ActionResult();
        $actionResult->setViewModel($vm);

        $actionResult->layout()->setTemplate('layout/layout-govuk.phtml');
        $actionResult->layout()->setBreadcrumbs($breadcrumbs);

        return $actionResult;
    }
}
