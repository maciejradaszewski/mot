<?php

namespace AccountApiTest\Controller;

use AccountApi\Service\ClaimService;
use DvsaCommonApiTest\Controller\AbstractRestfulControllerTestCase;
use DvsaCommonTest\TestUtils\XMock;
use AccountApi\Controller\ClaimController;

/**
 * Class ClaimControllerTest
 */
class ClaimControllerTest extends AbstractRestfulControllerTestCase
{
    CONST PIN = '123456';
    const PERSON_ID = 1;

    private $service;

    protected function setUp()
    {
        $this->service = XMock::of(
            ClaimService::class
        );

        $this->setController(new ClaimController($this->service));

        parent::setUp();

        $this->serviceManager->setService(ClaimService::class, $this->service);
    }


    public function testClaimControllerGet()
    {
        $this->service->expects($this->once())
            ->method('generateClaimAccountData')
            ->willReturn(self::PIN);

        $result = $this->getResultForAction('get', null, ['id' => self::PERSON_ID]);
        $this->assertResponseStatusAndResult(self::HTTP_OK_CODE, ['data' => self::PIN], $result);
    }

    public function testClaimControllerUpdate()
    {
        $this->service->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $result = $this->getResultForAction('put', null, ['id' => self::PERSON_ID], null, []);

        $this->assertResponseStatusAndResult(self::HTTP_OK_CODE, ['data' => true], $result);
    }
}
