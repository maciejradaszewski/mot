<?php

namespace Site\UpdateVtsProperty;

use Core\Controller\AbstractAuthActionController;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;

class UpdateVtsPropertyController extends AbstractAuthActionController implements AutoWireableInterface
{
    private $updateAction;
    private $reviewAction;

    public function __construct(
        UpdateVtsPropertyAction $updateAction,
        UpdateVtsPropertyReviewAction $reviewAction
    )
    {
        $this->updateAction = $updateAction;
        $this->reviewAction = $reviewAction;
    }

    public function editAction()
    {
        $propertyName = $this->params()->fromRoute('propertyName');
        $isPost = $this->getRequest()->isPost();
        $vtsId = $this->params()->fromRoute('id');
        $formData = $this->getRequest()->getPost()->getArrayCopy();
        $formUuid = $this->params()->fromQuery('formUuid');

        $actionResult = $this->updateAction->execute($isPost, $propertyName, $vtsId, $formUuid, $formData);

        return $this->applyActionResult($actionResult);
    }

    public function reviewAction()
    {
        $propertyName = $this->params()->fromRoute('propertyName');
        $isPost = $this->getRequest()->isPost();
        $vtsId = $this->params()->fromRoute('id');
        $formUuid = $this->params()->fromRoute('formUuid');

        $actionResult = $this->reviewAction->execute($isPost, $propertyName, $vtsId, $formUuid);

        return $this->applyActionResult($actionResult);
    }
}
