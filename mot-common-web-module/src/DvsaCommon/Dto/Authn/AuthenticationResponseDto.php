<?php
namespace DvsaCommon\Dto\Authn;

use DvsaCommon\Dto\Common\KeyValue;
use DvsaCommon\DtoSerialization\ReflectiveDtoInterface;

class AuthenticationResponseDto implements ReflectiveDtoInterface
{
    /** @var string  */
    private $accessToken;

    /** @var AuthenticatedUserDto */
    private $user;

    /** @var int */
    private $authnCode;

    /** @var  array */
    private $extra;

    /** @var int  */
    private $code;
    /**
     * @var array
     */
    private $messages;

    /** @var bool */
    private $isValid;

    /** @var  string */
    private $identity;

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     * @return AuthenticationResponseDto
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @return AuthenticatedUserDto
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \DvsaCommon\Dto\Authn\AuthenticatedUserDto $user
     * @return AuthenticationResponseDto
     */
    public function setUser(\DvsaCommon\Dto\Authn\AuthenticatedUserDto $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return int
     */
    public function getAuthnCode()
    {
        return $this->authnCode;
    }

    /**
     * @param int $authnCode
     * @return AuthenticationResponseDto
     */
    public function setAuthnCode($authnCode)
    {
        $this->authnCode = $authnCode;
        return $this;
    }

    /**
     * @return KeyValue[]
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * @param \DvsaCommon\Dto\Common\KeyValue[] $extra
     * @return AuthenticationResponseDto
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;
        return $this;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     * @return AuthenticationResponseDto
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param string[] $messages
     * @return AuthenticationResponseDto
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isIsValid()
    {
        return $this->isValid;
    }

    /**
     * @param boolean $isValid
     * @return AuthenticationResponseDto
     */
    public function setIsValid($isValid)
    {
        $this->isValid = $isValid;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @param string $identity
     * @return AuthenticationResponseDto
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
        return $this;
    }
}