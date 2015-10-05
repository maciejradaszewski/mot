<?php

namespace TestSupport\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use TestSupport\Helper\TestDataResponseHelper;
use TestSupport\Service\DocumentService;

class DocumentController extends BaseTestSupportRestfulController {

    public function get($id)
    {
        $documentService = $this->getServiceLocator()->get(DocumentService::class);

        return TestDataResponseHelper::jsonOk($documentService->get($id));
    }

    public function delete($id)
    {
        $documentService = $this->getServiceLocator()->get(DocumentService::class);

        return TestDataResponseHelper::jsonOk($documentService->delete($id));
    }
}