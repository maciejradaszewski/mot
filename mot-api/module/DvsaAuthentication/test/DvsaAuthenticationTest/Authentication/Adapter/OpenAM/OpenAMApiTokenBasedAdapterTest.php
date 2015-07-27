<?php

namespace DvsaAuthenticationTest\Authentication\Adapter\OpenAM;

use Dvsa\OpenAM\Exception\OpenAMClientException;
use Dvsa\OpenAM\Exception\OpenAMUnauthorisedException;
use Dvsa\OpenAM\OpenAMClientInterface;
use DvsaAuthentication\Authentication\Adapter\OpenAM\OpenAMApiTokenBasedAdapter;
use DvsaAuthentication\Service\ApiTokenService;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\Person;
use DvsaEntities\Repository\PersonRepository;
use Zend\Authentication\Result;
use Zend\Log\LoggerInterface;

class OpenAMApiTokenBasedAdapterTest extends \PHPUnit_Framework_TestCase
{
    const ATTR_USERNAME = 'usernameAttr';
    const EXAMPLE_USERNAME = 'exampleUsername';
    const EXAMPLE_UUID = 'cfe83655-a02b-4caa-b951-2f53dd56ec32';
    const ATTR_UUID = 'uuidAttr';
    const EXAMPLE_TOKEN = 'valid_token';

    private $client;
    private $tokenService;
    private $personRepo;

    public function setUp()
    {
        $this->client = XMock::of(OpenAMClientInterface::class);
        $this->personRepo = XMock::of(PersonRepository::class);
        $this->tokenService = XMock::of(ApiTokenService::class);
    }
    
    public function testAuthenticate_tokenNotFound_shouldReturnInvalidCredential()
    {
        $this->tokenWasFound(false);

        $result = $this->createAdapter()->authenticate();

        $this->assertEquals(Result::FAILURE_CREDENTIAL_INVALID, $result->getCode());
    }

    public static function dataProvider_incompleteAttributes()
    {
        return [[self::ATTR_USERNAME], [self::ATTR_UUID]];
    }

    /**
     * @dataProvider dataProvider_incompleteAttributes
     */
    public function testAuthenticate_incompleteAttributes_shouldReturnFailure($incompleteAttr)
    {
        $this->tokenWasFound();
        $this->identityAttributesResolvedTo($this->identityAttributes_without($incompleteAttr));

        $result = $this->createAdapter()->authenticate();

        $this->assertEquals(Result::FAILURE, $result->getCode());
    }

    public function testAuthenticate_openAMClientThrownException_shouldReturnFailure()
    {
        $this->tokenWasFound();
        $this->identityAttributesFailed(new OpenAMClientException("Error"));

        $result = $this->createAdapter()->authenticate();

        $this->assertEquals(Result::FAILURE, $result->getCode());
    }

    public function testAuthenticate_openAMClientThrownUnauthorised_shouldReturnInvalidCredentials()
    {
        $this->tokenWasFound();
        $this->identityAttributesFailed(new OpenAMUnauthorisedException("Error"));

        $result = $this->createAdapter()->authenticate();

        $this->assertEquals(Result::FAILURE_CREDENTIAL_INVALID, $result->getCode());
    }

    public function testAuthenticate_personNotFound_shouldReturnFailure()
    {
        $this->tokenWasFound();
        $this->identityAttributesResolvedTo($this->identityAttributes_valid());
        $this->personWasFound(false);

        $result = $this->createAdapter()->authenticate();

        $this->assertEquals(Result::FAILURE, $result->getCode());
    }

    public function testAuthenticate_success_shouldReturnValidIdentity()
    {
        $this->tokenWasFound();
        $this->identityAttributesResolvedTo($this->identityAttributes_valid());
        $this->personWasFound();

        $result = $this->createAdapter()->authenticate();

        $this->assertEquals(Result::SUCCESS, $result->getCode());
        $identity = $result->getIdentity();
        $this->assertNotNull($identity);
        $this->assertEquals(self::EXAMPLE_UUID, $identity->getUuid());
        $this->assertEquals(self::EXAMPLE_USERNAME, $identity->getPerson()->getUsername());
        $this->assertEquals(self::EXAMPLE_TOKEN, $identity->getToken());
    }

    private function createAdapter()
    {
        return new OpenAMApiTokenBasedAdapter(
            $this->client,
            self::ATTR_USERNAME,
            $this->personRepo,
            XMock::of(LoggerInterface::class),
            $this->tokenService,
            self::ATTR_UUID
        );
    }

    private function tokenWasFound($isParsed = true)
    {
        $this->tokenService->expects($this->atLeastOnce())->method('parseToken')
            ->willReturn($isParsed ? self::EXAMPLE_TOKEN : null);
    }

    private function identityAttributesResolvedTo($identityAttrs)
    {
        $this->client->expects($this->once())->method('getIdentityAttributes')
            ->willReturn($identityAttrs);
    }

    private function identityAttributesFailed($exception)
    {
        $this->client->expects($this->once())->method('getIdentityAttributes')
            ->willThrowException($exception);

    }

    private static function identityAttributes_valid()
    {
        return [
            self::ATTR_USERNAME => self::EXAMPLE_USERNAME,
            self::ATTR_UUID => self::EXAMPLE_UUID
        ];
    }

    private static function identityAttributes_without($missingAttr)
    {
        $attrs = self::identityAttributes_valid();
        unset($attrs[$missingAttr]);

        return $attrs;
    }

    private function personWasFound($isFound = true)
    {
        $this->personRepo->expects($this->atLeastOnce())
            ->method('findOneBy')->with(['username' => self::EXAMPLE_USERNAME])
            ->willReturn($isFound ? (new Person())->setUsername(self::EXAMPLE_USERNAME) : null);
    }
}
