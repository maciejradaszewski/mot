<?php

namespace DvsaCommonApiTest\Service;

use DvsaCommonApi\Service\SeqNumberService;
use DvsaCommonTest\TestUtils\XMock;
use \PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class SeqNumberServiceTest
 *
 * @package DvsaCommonApiTest\Service
 */
class SeqNumberServiceTest extends AbstractServiceTestCase
{
    const CODE = 'SITE';
    const RESULT = 'Amazing';

    public function testGetNextSeqNumber()
    {
        /**
         * @var \Doctrine\ORM\EntityManager|MockObject $entityManager
         */
        $entityManager = XMock::of(\Doctrine\ORM\EntityManager::class);
        /**
         * @var \Doctrine\DBAL\Connection|MockObject $connexion
         */
        $connection = XMock::of(\Doctrine\DBAL\Connection::class);
        /**
         * @var \Doctrine\DBAL\Driver\Statement|MockObject $connexion
         */
        $statement = XMock::of(\Doctrine\DBAL\Driver\Statement::class);

        $service = new SeqNumberService($entityManager);

        $entityManager->expects($this->once())
            ->method('getConnection')
            ->willReturn($connection);

        $connection->expects($this->once())
            ->method('prepare')
            ->willReturn($statement);

        $statement->expects($this->once())
            ->method('fetch')
            ->willReturn($this->getResults());

        $statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->assertEquals(self::RESULT, $service->getNextSeqNumber(self::CODE));
    }

    private function getResults()
    {
        return [
            'sequence' => self::RESULT
        ];
    }
}
