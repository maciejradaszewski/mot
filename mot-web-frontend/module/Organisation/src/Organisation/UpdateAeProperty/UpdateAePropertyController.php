<?php

namespace Organisation\UpdateAeProperty;

use Core\Controller\AbstractAuthActionController;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;

class UpdateAePropertyController extends AbstractAuthActionController implements AutoWireableInterface
{
    private $updateAction;
    private $reviewAction;

    public function __construct(
        UpdateAePropertyAction $updateAction,
        UpdateAePropertyReviewAction $reviewAction
    )
    {
        $this->updateAction = $updateAction;
        $this->reviewAction = $reviewAction;
    }

    public function editAction()
    {
        $propertyName = $this->params()->fromRoute('propertyName');
        $isPost = $this->getRequest()->isPost();
        $aeId = $this->params()->fromRoute('id');
        $formData = $this->getRequest()->getPost()->getArrayCopy();
        $formUuid = $this->params()->fromQuery('formUuid');

        $actionResult = $this->updateAction->execute($isPost, $propertyName, $aeId, $formUuid, $formData);

        return $this->applyActionResult($actionResult);
    }

    public function reviewAction()
    {
        $propertyName = $this->params()->fromRoute('propertyName');
        $isPost = $this->getRequest()->isPost();
        $aeId = $this->params()->fromRoute('id');
        $formUuid = $this->params()->fromRoute('formUuid');

        $actionResult = $this->reviewAction->execute($isPost, $propertyName, $aeId, $formUuid);

        return $this->applyActionResult($actionResult);
    }
}
