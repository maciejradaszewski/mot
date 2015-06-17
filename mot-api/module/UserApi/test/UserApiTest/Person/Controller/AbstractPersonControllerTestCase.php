<?php

namespace UserApiTest\Person\Controller;

use DvsaCommonApiTest\Controller\ApiControllerUnitTestInterface;
use DvsaCommonApiTest\Controller\ApiControllerUnitTestTrait;
use OrganisationApi\Service\AuthorisedExaminerService;
use UserApi\Person\Dto\MotTestingAuthorisationCollector;
use UserApi\Person\Service\PersonalAuthorisationForMotTestingService;
use UserApi\Person\Service\PersonalDetailsService;
use UserApi\Person\Service\PersonService;
use Zend\Stdlib\Parameters;

/**
 * Base for common application controller test in UserApi\Person namespace
 */
abstract class AbstractPersonControllerTestCase extends \PHPUnit_Framework_TestCase implements
    ApiControllerUnitTestInterface
{
    use ApiControllerUnitTestTrait;

    public function mockServices()
    {
        $this->createMock(AuthorisedExaminerService::class);
        $service = $this->createMock(PersonalAuthorisationForMotTestingService::class);
        $this->createMock(PersonalDetailsService::class);
        $this->createMock(PersonService::class);

        $service->expects($this->any())
            ->method('updatePersonalTestingAuthorisationGroup')
            ->will($this->returnValue(new MotTestingAuthorisationCollector([])));
        $service->expects($this->any())
            ->method('getPersonalTestingAuthorisation')
            ->will($this->returnValue(new MotTestingAuthorisationCollector([])));
    }
}
