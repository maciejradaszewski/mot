<?php
namespace DvsaAuthentication\Identity\OpenAM;

use Dvsa\OpenAM\Exception\OpenAMClientException;
use Dvsa\OpenAM\Exception\OpenAMUnauthorisedException;
use Dvsa\OpenAM\OpenAMClient;
use Dvsa\OpenAM\OpenAMClientInterface;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use DvsaAuthentication\Identity\IdentityByTokenResolver;
use DvsaAuthentication\IdentityFactory;
use Zend\Log\LoggerInterface;

class OpenAMIdentityByTokenResolver implements IdentityByTokenResolver
{
    /** @var OpenAMClientOptions $openAMOptions */
    private $openAMOptions;

    /** @var OpenAMClientInterface $openAMClient */
    private $openAMClient;

    /** @var LoggerInterface */
    private $logger;

    /** @var  IdentityFactory */
    private $identityFactory;

    public function __construct(
        OpenAMClientInterface $openAMClient,
        OpenAMClientOptions $openAMClientOptions,
        LoggerInterface $logger,
        IdentityFactory $identityFactory
    ) {
        $this->openAMClient = $openAMClient;
        $this->openAMOptions = $openAMClientOptions;
        $this->logger = $logger;
        $this->identityFactory = $identityFactory;
    }

    public function resolve($token)
    {
        try {
            $identityAttrs = $this->openAMClient->getIdentityAttributes($token);
        } catch (OpenAMClientException $clientEx) {
            $this->logger->err('OpenAM client - message: ' . $clientEx->getMessage());
            return null;
        }

        $username = $this->findIdentityAttribute($this->openAMOptions->getIdentityAttributeUsername(), $identityAttrs);
        if (empty($username)) {
            $this->logger->err($this->openAMOptions->getIdentityAttributeUsername() . ' not found in identity attributes!');
            return null;
        }

        $uuid = $this->findIdentityAttribute($this->openAMOptions->getIdentityAttributeUuid(), $identityAttrs);
        if (empty($uuid)) {
            $this->logger->err($this->openAMOptions->getIdentityAttributeUuid() . ' not found in identity attributes!');
            return null;
        }

        return $this->identityFactory->create($username, $token, $uuid, null);
    }


//    private function parsePasswordExpiryDate($attr, $attrs)
//    {
//        $expiryDateString = $this->findIdentityAttribute($attr, $attrs);
//
//        try {
//            $dateTime = $this->parseDateFromLdap($expiryDateString);
//
//            return $dateTime;
//        } catch (\Exception $e) {
//            $this->logger->err('Error parsing passwordExpiryDate');
//        }
//        return null;
//    }


    /**
     * Searches for attribute inside identity attributes map
     * @param string $attribute
     * @param array $map
     * @return string|null attribute value or null if the value was not found
     */
    protected function findIdentityAttribute($attribute, $map)
    {
        if (count($map) === 0) {
            return null;
        }

        foreach ($map as $key => $val) {
            if (strtolower($key) === strtolower($attribute)) {
                return $val;
            }
        }

        return null;
    }

    private function parseDateFromLdap($string)
    {
        // as: DateTime::createFromFormat("Ymdhis", $expiryDateString);
        // failed, a manual solution was chosen because of lack of time
        // feel free to update this method

        $year = substr($string, 0, 4);
        $month = substr($string, 4, 2);
        $day = substr($string, 6, 2);
        $hour = substr($string, 8, 2);
        $minute = substr($string, 10, 2);
        $second = substr($string, 12, 2);

        $dateTime = new \DateTime();
        $dateTime->setDate($year, $month, $day);
        $dateTime->setTime($hour, $minute, $second);

        return $dateTime;
    }


}