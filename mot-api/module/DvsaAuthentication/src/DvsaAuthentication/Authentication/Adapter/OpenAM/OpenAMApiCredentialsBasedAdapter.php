<?php

namespace DvsaAuthentication\Authentication\Adapter\OpenAM;

use Doctrine\ORM\EntityManager;
use Dvsa\OpenAM\Exception\OpenAMClientException;
use Dvsa\OpenAM\Exception\OpenAMUnauthorisedException;
use Dvsa\OpenAM\Model\OpenAMLoginDetails;
use Dvsa\OpenAM\OpenAMClientInterface;
use DvsaAuthentication\Authentication\Adapter\AuthenticationAdapterTrait;
use DvsaAuthentication\Identity;
use DvsaCommon\Log\Logger;
use DvsaEntities\Entity\Person;
use DvsaEntities\Repository\PersonRepository;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Http\PhpEnvironment\Request;
use Zend\Log\LoggerInterface;

class OpenAMApiCredentialsBasedAdapter implements AdapterInterface
{
    use AuthenticationAdapterTrait;

    /** @var \DvsaEntities\Repository\PersonRepository */
    private $personRepository;
    /** @var \Dvsa\OpenAM\OpenAMClientInterface */
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
        PersonRepository $personRepository,
        LoggerInterface $logger,
        $uuidAttribute
    ) {
        $this->personRepository = $personRepository;
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

        /** @var Person $person */
        $person = $this->personRepository->findOneBy(['username' => $this->username]);
        if (is_null($person)) {
            $this->logger->err('Person: ' . $this->username . ' not found in database!');

            return self::identityResolutionFailedResult();
        }

        $identity = (new Identity($person))->setToken($token)->setUuid($uuid);

        return new Result(Result::SUCCESS, $identity);
    }
}
