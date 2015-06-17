<?php
namespace DvsaCommonApiTest\Service;

use DvsaCommonApi\Service\AbstractService;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;

/**
 * Class AbstractServiceTest
 */
class AbstractServiceTest extends AbstractServiceTestCase
{
    public function testConstructor()
    {
        $mockEntityManager = $this->getMockEntityManager();
        $testAbstractService = new TestAbstractService($mockEntityManager);
    }
}

/**
 * Class TestAbstractService
 */
class TestAbstractService extends AbstractService
{
}
