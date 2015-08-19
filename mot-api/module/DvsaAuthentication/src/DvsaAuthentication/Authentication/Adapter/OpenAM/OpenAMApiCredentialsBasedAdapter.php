<?php

namespace DvsaAuthentication\Authentication\Adapter\OpenAM;

use Doctrine\ORM\EntityManager;
use Dvsa\OpenAM\Exception\OpenAMClientException;
use Dvsa\OpenAM\Exception\OpenAMUnauthorisedException;
use Dvsa\OpenAM\Model\OpenAMLoginDetails;
use Dvsa\OpenAM\OpenAMClientInterface;
use DvsaAuthentication\Authentication\Adapter\AuthenticationAdapterTrait;
use DvsaAuthentication\IdentityFactory;
use DvsaEntities\Repository\PersonRepository;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Http\PhpEnvironment\Request;
use Zend\Log\LoggerInterface;

class OpenAMApiCredentialsBasedAdapter implements AdapterInterface
{
    use AuthenticationAdapterTrait;

    /**
     * @var IdentityFactory
     */
    private $identityFactory;

    /**
     * @var OpenAMClientInterface
     */
    private $openAMClient;

    private $username;
    private $password;
    private $uuidAttribute;
    private $logger;

    /**
     * @param OpenAMClientInterface $openAMClient
     * @param                       $realm
     * @param PersonRepository $personRepository
     * @param LoggerInterface $logger
     * @param string $uuidAttribute
     */
    public function __construct(
        OpenAMClientInterface $openAMClient,
        $realm,
        IdentityFactory $identityFactory,
        LoggerInterface $logger,
        $uuidAttribute
    ) {
        $this->identityFactory = $identityFactory;
        $this->openAMClient = $openAMClient;
        $this->realm = $realm;
        $this->uuidAttribute = $uuidAttribute;
        $this->logger = $logger;
    }

    public function setCredentials($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function authenticate()
    {
        $loginDetails = new OpenAMLoginDetails($this->username, $this->password, $this->realm);
        try {
            $token = $this->openAMClient->authenticate($loginDetails);
            $identityAttrs = $this->openAMClient->getIdentityAttributes($token);
        } catch (OpenAMUnauthorisedException $ex) {
            $this->logger->debug('OpenAM client - message ' . $ex->getMessage());

            return self::invalidTokenResult();
        } catch (OpenAMClientException $clientEx) {
            $this->logger->err('OpenAM client - message ' . $clientEx->getMessage());

            return self::identityResolutionFailedResult();
        }

        $uuid = $this->findIdentityAttribute($this->uuidAttribute, $identityAttrs);
        if (empty($uuid)) {
            $this->logger->err($this->uuidAttribute . ' not found in identity attributes!');

            return self::identityResolutionFailedResult();
        }

        try {
            $identity = $this->identityFactory->create($this->username, $token, $uuid);

            return new Result(Result::SUCCESS, $identity);
        } catch (\InvalidArgumentException $e) {
            $this->logger->err(sprintf('Person: %s not found in database!', $this->username));

            return self::identityResolutionFailedResult();
        }
    }
}
