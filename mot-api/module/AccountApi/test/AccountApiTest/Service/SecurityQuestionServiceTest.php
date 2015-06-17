<?php

namespace AccountApiTest\Service;

use DvsaCommon\Dto\Security\SecurityQuestionDto;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommonTest\Bootstrap;
use DvsaEntities\Entity\SecurityQuestion;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\XMock;
use AccountApi\Mapper\SecurityQuestionMapper;
use AccountApi\Service\SecurityQuestionService;
use DvsaEntities\Repository\SecurityQuestionRepository;
use Zend\ServiceManager\ServiceManager;
use PHPUnit_Framework_MockObject_MockObject as MockObj;

/**
 * Class SecurityQuestionServiceTest
 * @package AccountApiTest\Service
 */
class SecurityQuestionServiceTest extends AbstractServiceTestCase
{
    const USER_ID = 9999;
    const QUESTION_ID = 8888;

    /** @var ServiceManager */
    protected $serviceManager;

    protected $mockEntityManager;
    /** @var   SecurityQuestionRepository|MockObj */
    protected $mockSqRepo;
    /** @var  ParamObfuscator|MockObj */
    private $mockParamObfuscator;

    /** @var  SecurityQuestionService */
    protected $service;

    protected function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $this->serviceManager->setAllowOverride(true);

        //  --  mock repo   --
        $this->mockSqRepo = XMock::of(
            SecurityQuestionRepository::class,
            ['isAnswerCorrect', 'findAll', 'findQuestionByQuestionNumber']
        );
        $this->serviceManager->setService(SecurityQuestionRepository::class, $this->mockSqRepo);

        //  --  mock entity manager --
        $this->mockEntityManager = $this->getMockEntityManager();
        $this->mockMethod(
            $this->mockEntityManager, 'getRepository', $this->any(), $this->mockSqRepo, SecurityQuestion::class
        );

        //  --  mock obfuscator --
        $this->mockParamObfuscator = XMock::of(ParamObfuscator::class);
        $this->serviceManager->setService(ParamObfuscator::class, $this->mockParamObfuscator);

        //  --
        $this->service = new SecurityQuestionService(
            $this->mockSqRepo,
            new SecurityQuestionMapper(),
            $this->mockParamObfuscator
        );
    }

    /**
    * @expectedException \Exception
    */
    public function testThrowsExceptionWhenQuestionIdIsNonInteger()
    {
        $this->service->isAnswerCorrect([], 1, '');
    }

    /**
    * @expectedException \Exception
    */
    public function testThrowsExceptionWhenUserIdIsNonInteger()
    {
        $this->service->isAnswerCorrect(1, [], '');
    }

    /**
    * @expectedException \Exception
    */
    public function testThrowsExceptionWhenAnswerIsNonString()
    {
        $this->service->isAnswerCorrect(42, 1, []);
    }

    /**
     * @dataProvider dataProviderTestCorrectlyHandlesFailedQuestionByReturning
     */
    public function testCorrectlyHandlesFailedQuestionByReturningFalse($result, $expect)
    {
        $answer = 'pointless answer';

        $this->mockMethod(
            $this->mockSqRepo, 'isAnswerCorrect', $this->once(), $result, [self::QUESTION_ID, self::USER_ID, $answer]
        );

        $actual = $this->service->isAnswerCorrect(self::QUESTION_ID, self::USER_ID, $answer);

        $this->assertEquals($expect, $actual);
    }

    public function dataProviderTestCorrectlyHandlesFailedQuestionByReturning()
    {
        return [
            [true, true],
            [false, false],
        ];
    }

    public function testFindAll()
    {
        $entity = new SecurityQuestion();
        $dto = new SecurityQuestionDto();

        $this->mockSqRepo->expects($this->once())
            ->method('findAll')
            ->willReturn([$entity]);

        $this->assertEquals([$dto], $this->service->getAll());
    }

    public function testFindQuestionByNumber()
    {
        $entity = new SecurityQuestion();
        $dto = new SecurityQuestionDto();
        $qid = 42;
        $uid = 1;

        $this->mockSqRepo->expects($this->once())
            ->method('findQuestionByQuestionNumber')
            ->with($qid, $uid)
            ->willReturn($entity);

        $this->assertEquals($dto, $this->service->findQuestionByQuestionNumber($qid, $uid));
    }

    /**
     * @expectedException \Exception
     */
    public function testFindQuestionByNumberThrowsException()
    {
        $this->service->findQuestionByQuestionNumber('invalid', 'invalid');
    }
}
