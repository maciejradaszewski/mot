<?php

namespace DvsaCommon\Dto\Organisation\Payment;

/**
 * CPMS call html form data.
 */
class CpmsCallParametersDto
{
    /**
     * @var string CPMS interface base url
     */
    private $gatewayUrl;

    /**
     * @var string
     */
    private $grantType;

    /**
     * @var string DVSA service id in CPMS
     */
    private $clientId;

    /**
     * @var string service authorization data
     */
    private $clientSecret;

    /**
     * @var string payment scope
     */
    private $scope;

    /**
     * @var string ?
     */
    private $userId;

    /**
     * @param string $clientId
     * @return $this
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $clientSecret
     * @return $this
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param string $gatewayUrl
     * @return $this
     */
    public function setGatewayUrl($gatewayUrl)
    {
        $this->gatewayUrl = $gatewayUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getGatewayUrl()
    {
        return $this->gatewayUrl;
    }

    /**
     * @param string $grantType
     * @return $this
     */
    public function setGrantType($grantType)
    {
        $this->grantType = $grantType;
        return $this;
    }

    /**
     * @return string
     */
    public function getGrantType()
    {
        return $this->grantType;
    }

    /**
     * @param string $scope
     * @return $this
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param string $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
