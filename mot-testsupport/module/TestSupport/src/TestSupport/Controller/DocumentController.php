<?php

namespace TestSupport\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use TestSupport\Helper\TestDataResponseHelper;

class DocumentController extends BaseTestSupportRestfulController {

    public function get($id)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getServiceLocator()->get(EntityManager::class);

        /** @var Connection $connection */
        $connection = $entityManager->getConnection();

        $document = $connection->fetchAll("SELECT * FROM jasper_document WHERE id = :id", [
            'id' => $id
        ]);

        return TestDataResponseHelper::jsonOk($document);
    }

    public function delete($id)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getServiceLocator()->get(EntityManager::class);

        /** @var Connection $connection */
        $connection = $entityManager->getConnection();

        $connection->exceuteQuery("UPDATE mot_test SET document_id = NULL WHERE id = :id", [
            'id' => $id
        ]);

        $result = $connection->executeQuery("DELETE FROM jasper_document WHERE id = :id", [
            'id' => $id
        ]);

        return TestDataResponseHelper::jsonOk($result);
    }
}