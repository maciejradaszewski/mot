<?php
namespace DvsaCommon\Dto\Common;

use DvsaCommon\DtoSerialization\ReflectiveDtoInterface;

class KeyValue implements ReflectiveDtoInterface
{

    /** @var string $key */
    private $key;

    /** @var string $value */
    private $value;


    public function __construct($key = null, $value = null)
    {
        $this->key = $key;
        $this->value = $value;
    }


    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return KeyValue
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return KeyValue
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Converts a map into list of KeyValue objects
     * @param $map
     * @return KeyValue[]
     */
    public static function fromMap($map)
    {
        if (is_null($map)) {
            return [];
        }

        $keyValueList = [];
        foreach ($map as $key => $value) {
            $keyValueList[] = new KeyValue($key, $value);
        }
        return $keyValueList;
    }

    /**
     * @param KeyValue[] $list
     * @param $key
     * @param $default
     * @return null
     */
    public static function find($list, $key, $default = null)
    {

        if ($list == null) {
            return null;
        }
        foreach ($list as $elem) {
            if ($elem->getKey() === $key) {
                return $elem->getValue();
            }
        }
        return $default;
    }
}