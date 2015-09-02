<?php

namespace DvsaCommon\Dto\Account;


use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\CommonTrait\EnumTypeDtoTrait;
use DvsaCommon\Utility\ArrayUtils;

/**
 * DTO for Authentication Method
 * @package DvsaCommon\Dto\Account
 */
class AuthenticationMethodDto extends AbstractDataTransferObject
{
    use EnumTypeDtoTrait;

    /** @var  string */
    private $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param array $data
     *
     * @return AuthenticationMethodDto
     */
    public static function fromArray($data)
    {
        $authenticationMethod = new self();
        $authenticationMethod
            ->setName(ArrayUtils::tryGet($data, 'name'))
            ->setCode(ArrayUtils::tryGet($data, 'code'));
        return $authenticationMethod;
    }

    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'code' => $this->getCode(),
        ];
    }

}