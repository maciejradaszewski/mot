<?php

namespace DvsaCommonApi\Filter;

use DvsaCommon\Dto\AbstractDataTransferObject;
use HTMLPurifier;
use ReflectionClass;
use ReflectionProperty;
use Zend\Filter\Exception\RuntimeException;

/**
 * Class XssFilter.
 */
class XssFilter implements FilterInterface
{
    /**
     * @var \HTMLPurifier
     */
    protected $htmlPurifier;

    /**
     * @param \HTMLPurifier $htmlPurifier
     */
    public function __construct(HTMLPurifier $htmlPurifier)
    {
        $this->htmlPurifier = $htmlPurifier;
    }

    /**
     * Returns the result of filtering $value.
     *
     * @param mixed $value
     *
     * @throws \Zend\Filter\Exception\RuntimeException If filtering $value is impossible
     *
     * @return mixed
     */
    public function filter($value)
    {
        if ($value instanceof AbstractDataTransferObject) {
            return  $this->filterDto($value);
        } elseif (is_array($value)) {
            return $this->filterMultiple($value);
        }

        return (!empty($value) ? $this->htmlPurifier->purify($value) : $value);
    }

    /**
     * {@inheritdoc}
     */
    public function filterMultiple(array $values)
    {
        foreach ($values as $key => $val) {
            $values[$key] = $this->filter($val);
        }

        return $values;
    }

    public function filterDto(AbstractDataTransferObject $dto)
    {
        $reflect = new ReflectionClass($dto);
        $props   = $reflect->getProperties(
            ReflectionProperty::IS_PRIVATE | ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PUBLIC
        );

        foreach ($props as $prop) {
            $prop->setAccessible(true);

            $val = $prop->getValue($dto);
            if (!empty($val)) {
                $prop->setValue($dto, $this->filter($val));
            }
        }

        return $dto;
    }
}
