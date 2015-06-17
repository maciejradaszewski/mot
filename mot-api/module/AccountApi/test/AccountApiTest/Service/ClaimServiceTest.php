<?php

namespace AccountApiTest\Service;

use AccountApi\Service\ClaimService;
use AccountApi\Service\OpenAmIdentityService;
use AccountApi\Service\Validator\ClaimValidator;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use DvsaAuthentication\Identity;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Date\DateTimeHolder;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\PersonSecurityAnswer;
use DvsaEntities\Entity\SecurityQuestion;
use DvsaEntities\Repository\PersonRepository;
use DvsaEntities\Repository\SecurityQuestionRepository;
use DvsaEventApi\Service\EventService;
use AccountApi\Mapper\SecurityQuestionMapper;
use AccountApi\Service\SecurityQuestionService;
use PHPUnit_Framework_MockObject_MockObject as MockObj;

class ClaimServiceTest extends AbstractServiceTestCase
{
    const SECURITY_QUESTION_ONE_ID = 1;

    const PASSWORD = 'Password123';

    /** @var ClaimService $sut service under test */
    private $sut;

    /** @var ClaimValidator */
    private $claimValidator;

    /** @var EntityManager|MockObj */
    protected $mockEntityManager;

    protected $mockPersonSecurityAnswerRepository;

    protected $mockSecurityQuestionRepository;

    protected $mockSecurityQuestionService;

    protected $mockIdentityService;

    protected $mockPersonRepository;

    protected $contactDetailsValidator;

    protected $mockOpenAmIdentityService;

    protected $mockEventService;

    /** @var  ParamObfuscator|MockObj */
    private $mockParamObfuscator;

    public function setUp()
    {
        $this->mockEntityManager = $this->getMockEntityManager();

        $this->mockSecurityQuestionRepository = XMock::of(SecurityQuestionRepository::class);
        $this->mockMethod($this->mockSecurityQuestionRepository, 'find', null, $this->getMockSecurityQuestion());

        $this->mockPersonRepository = XMock::of(PersonRepository::class);

        $this->mockOpenAmIdentityService = XMock::of(OpenAmIdentityService::class);
        $this->mockEventService = XMock::of(EventService::class);
        $this->mockParamObfuscator = XMock::of(ParamObfuscator::class);

        $this->mockSecurityQuestionService = new SecurityQuestionService(
            $this->mockSecurityQuestionRepository,
            new SecurityQuestionMapper(),
            $this->mockParamObfuscator
        );

        $this->mockIdentityService = $this->getMock(MotIdentityProviderInterface::class);
        $this->mockMethod($this->mockIdentityService, 'getIdentity', null, $this->getMockPerson());

        $this->claimValidator = new ClaimValidator(
            $this->mockSecurityQuestionService,
            $this->mockSecurityQuestionRepository
        );

        $this->sut = new ClaimService(
            $this->mockEntityManager,
            $this->mockIdentityService,
            $this->claimValidator,
            $this->mockSecurityQuestionRepository,
            $this->mockPersonRepository,
            $this->mockOpenAmIdentityService,
            $this->mockEventService,
            $this->mockParamObfuscator,
            new DateTimeHolder()
        );
    }

    public function testCannotSaveDataIfNotSupplied()
    {
        $this->setExpectedException('Exception', 'No data specified');
        $this->sut->save(null);
    }

    public function testUserNotSpecifiedCannotProcess()
    {
        $this->setExpectedException('Exception', 'Invalid Request');

        $data = $this->getPostData();
        unset($data['personId']);

        $this->sut->save($data);
    }

    public function testUserCannotClaimAnotherAccount()
    {
        $this->setExpectedException('Exception', 'Invalid Request');

        $data = $this->getPostData();
        $data['personId'] = 1;

        $this->sut->save($data);
    }

    public function testCanValidateCompleteData()
    {
        $this
            ->mockSecurityQuestionRepository
            ->expects($this->once())
            ->method('findAllByIds')
            ->willReturn([self::SECURITY_QUESTION_ONE_ID => $this->getMockSecurityQuestion()]);

        $data = $this->getPostData();
        $data['personId'] = 4;
        $data['password'] = self::PASSWORD;
        $data['passwordConfirmation'] = self::PASSWORD;

        $this->mockMethod($this->mockEventService, 'addEvent', $this->any(), true);

        $person = $this->getMockPerson();
        $person->getPerson()->addSecurityAnswer($this->getMockPersonSecurityAnswer());

        $this->mockMethod($this->mockPersonRepository, 'find', $this->any(), $person->getPerson());

        $save = $this->sut->save($data);

        $this->assertTrue($save);
    }

    public function testCanValidateCompleteDataReClaim()
    {
        $data = $this->getPostData();
        $data['personId'] = 4;
        $data['password'] = self::PASSWORD;
        $data['passwordConfirmation'] = self::PASSWORD;

        $this->mockMethod($this->mockEventService, 'addEvent', $this->any(), true);
        $this->mockMethod($this->mockEventService, 'isEventCreatedBy', $this->any(), true);

        $person = $this->getMockPerson();
        $person->getPerson()->addSecurityAnswer($this->getMockPersonSecurityAnswer());

        $this->mockMethod($this->mockPersonRepository, 'find', $this->any(), $person->getPerson());

        $securityQuestions = [
            $data['securityQuestionOneId'] => new SecurityQuestion(),
            $data['securityQuestionTwoId'] => new SecurityQuestion(),
        ];

        $this->mockSecurityQuestionRepository->expects($this->any())
            ->method('findAllByIds')
            ->willReturn($securityQuestions);

        $save = $this->sut->save($data);

        $this->assertTrue($save);
    }

    public function testAccountAlreadyClaimedCannotClaimAgain()
    {
        $this->setExpectedException('Exception');

        $data = $this->getPostData();
        $data['personId'] = 4;

        $person = $this->getMockPerson();
        $person->getPerson()->setAccountClaimRequired(false);

        $this->mockMethod($this->mockPersonRepository, 'find', null, $this->getMockPerson()->getPerson());
        $this->mockMethod($this->mockIdentityService, 'getIdentity', null, $this->getMockPerson());

        $this->sut->save($data);
    }

    protected function getMockPerson()
    {
        $person = new Person();
        $person->setId(4);
        $person->setFirstName('Bob');
        $person->setFamilyName('Gill');
        $person->setAccountClaimRequired(true);

        return new Identity($person);
    }

    protected function getPostData()
    {
        return [
            'personId' => 5,
            'email' => 'test@test.com',
            'emailConfirmation' => 'test@test.com',
            'emailOptOut' => true,
            'password' => self::PASSWORD,
            'passwordConfirmation' => self::PASSWORD,
            'securityQuestionOneId' => '1',
            'securityAnswerOne' => '1',
            'securityQuestionTwoId' => '1',
            'securityAnswerTwo' => '1'
        ];
    }

    public function getMockSecurityQuestion()
    {
        $securityQuestion = new SecurityQuestion('Who am i?', 1);
        $securityQuestion->setId(self::SECURITY_QUESTION_ONE_ID);

        return $securityQuestion;
    }

    public function getMockPersonSecurityAnswer()
    {
        $personSecurityAnswer = new PersonSecurityAnswer(
            $this->getMockSecurityQuestion(),
            new Person(),
            'test'
        );
        return $personSecurityAnswer;
    }

    public function testPrepareTestData()
    {
        //
        $queryMock = $this
            ->getMockBuilder(AbstractQuery::class)
            ->setMethods(array('setParameters', 'execute'))
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $queryMock
            ->expects($this->once())
            ->method('setParameters')
            ->willReturn($queryMock);
        $queryMock
            ->expects($this->once())
            ->method('execute')
            ->willReturn(1);

        $this
            ->mockEntityManager
            ->expects($this->any())
            ->method('createQuery')
            ->willReturn($queryMock);

        $claimData = $this->sut->generateClaimAccountData();

        $this->assertInternalType('string', $claimData->getPin());
    }
}
