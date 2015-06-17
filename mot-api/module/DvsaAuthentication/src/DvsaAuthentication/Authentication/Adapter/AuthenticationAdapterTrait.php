<?php
namespace DvsaAuthentication\Authentication\Adapter;

use Zend\Authentication\Result;

trait AuthenticationAdapterTrait
{
    public static function invalidTokenResult()
    {
        return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, ['Valid token required']);
    }

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

    public static function identityResolutionFailedResult()
    {
        return new Result(Result::FAILURE, null, ['Identity resolution failed']);
    }
}
