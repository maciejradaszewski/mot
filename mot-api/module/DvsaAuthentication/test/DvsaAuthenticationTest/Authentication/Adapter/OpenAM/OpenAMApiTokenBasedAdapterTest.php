<?php

namespace DvsaAuthenticationTest\Authentication\Adapter\OpenAM;

use Dvsa\OpenAM\Exception\OpenAMClientException;
use Dvsa\OpenAM\Exception\OpenAMUnauthorisedException;
use Dvsa\OpenAM\OpenAMClientInterface;
use DvsaAuthentication\Authentication\Adapter\OpenAM\OpenAMApiTokenBasedAdapter;
use DvsaAuthentication\Identity;
use DvsaAuthentication\IdentityFactory;
use DvsaAuthentication\Service\ApiTokenService;
use DvsaCommonTest\TestUtils\XMock;
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
    private $identityFactory;

    public function setUp()
    {
        $this->client = XMock::of(OpenAMClientInterface::class);
        $this->tokenService = XMock::of(ApiTokenService::class);
        $this->identityFactory = XMock::of(IdentityFactory::class);
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

    public function testAuthenticate_identityNotCreated_shouldReturnFailure()
    {
        $this->tokenWasFound();
        $this->identityAttributesResolvedTo($this->identityAttributes_valid());
        $this->identityWasNotCreated();

        $result = $this->createAdapter()->authenticate();

        $this->assertEquals(Result::FAILURE, $result->getCode());
    }

    public function testAuthenticate_success_shouldReturnValidIdentity()
    {
        $expectedIdentity = XMock::of(Identity::class);

        $this->tokenWasFound();
        $this->identityAttributesResolvedTo($this->identityAttributes_valid());
        $this->identityWasCreated($expectedIdentity);

        $result = $this->createAdapter()->authenticate();

        $this->assertEquals(Result::SUCCESS, $result->getCode());
        $this->assertSame($expectedIdentity, $result->getIdentity());
    }

    private function createAdapter()
    {
        return new OpenAMApiTokenBasedAdapter(
            $this->client,
            self::ATTR_USERNAME,
            $this->identityFactory,
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

    private function identityWasCreated(Identity $identity)
    {

        $this->identityFactory->expects($this->any())
            ->method('create')
            ->with(self::EXAMPLE_USERNAME, self::EXAMPLE_TOKEN, self::EXAMPLE_UUID)
            ->willReturn($identity);
    }

    private function identityWasNotCreated()
    {
        $this->identityFactory->expects($this->any())
            ->method('create')
            ->will($this->throwException(new \InvalidArgumentException()));
    }
}
