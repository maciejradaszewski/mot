<?php

namespace DvsaMotApiTest\Factory\Service\Validator;

use Doctrine\ORM\EntityManager;
use DvsaCommonTest\TestUtils\TestCaseTrait;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Repository\MotTestRepository;
use DvsaMotApi\Factory\Service\Validator\RetestEligibilityValidatorFactory;
use DvsaMotApi\Service\Validator\RetestEligibility\RetestEligibilityValidator;
use NonWorkingDaysApi\NonWorkingDaysHelper;
use Zend\Log\LoggerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use UserApi\SpecialNotice\Service\SpecialNoticeService;

/**
 * Class ClaimServiceFactoryTest
 * @package AccountApiTest\Factory
 */
class RetestEligibilityValidatorFactoryTest extends \PHPUnit_Framework_TestCase
{
    use TestCaseTrait;

    public function testEventServiceGetList()
    {
        $mockServiceLocator = XMock::of(ServiceLocatorInterface::class, ['get']);
        $this->mockMethod($mockServiceLocator, 'get', $this->at(0), XMock::of(NonWorkingDaysHelper::class));
        $this->mockMethod($mockServiceLocator, 'get', $this->at(1), XMock::of(MotTestRepository::class));

        $this->assertInstanceOf(
            RetestEligibilityValidator::class,
            (new RetestEligibilityValidatorFactory())->createService($mockServiceLocator)
        );
    }
}
